<?php declare(strict_types=1);
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EshopCommunity\Tests\Unit\Internal\Module\Install;

use OxidEsales\EshopCommunity\Core\Module\Module;
use OxidEsales\EshopCommunity\Internal\Module\Install\CopyGlobServiceInterface;
use OxidEsales\EshopCommunity\Internal\Module\Install\ModuleCopyService;
use OxidEsales\EshopCommunity\Internal\Module\Install\PackageServiceInterface;
use OxidEsales\EshopCommunity\Internal\Utility\FactsContext;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
class ModuleCopyServiceTest extends TestCase
{
    public function testCopyDispatchesCallsToTheCopyGlobService()
    {
        $packagePath = '/var/www/vendor/myvendor/mymodule';
        $packageName = 'myvendor/mymodule';
        $packageService = $this->getMockBuilder(PackageServiceInterface::class)
        ->setMethods(['getExtra', 'getPath', 'getName'])->getMock();
        $packageService->method('getExtra')->willReturn([]);
        $packageService->method('getPath')->willReturn($packagePath);
        $packageService->method('getName')->willReturn($packageName);

        $eshopSourcePath = '/var/www/oxideshop/source';
        $context = $this->getMockBuilder(FactsContext::class)
            ->setMethods(['getSourcePath'])->getMock();
        $context->method('getSourcePath')->willReturn($eshopSourcePath);

        $copyGlobService = $this->getMockBuilder(CopyGlobServiceInterface::class)
            ->setMethods(['copy'])->getMock();
        $copyGlobService->expects($this->any())
                        ->method('copy')
                        ->with(
                            $packagePath,
                            $eshopSourcePath . DIRECTORY_SEPARATOR . ModuleCopyService::MODULES_DIRECTORY . DIRECTORY_SEPARATOR . $packageName,
                            [ModuleCopyService::BLACKLIST_VCS_DIRECTORY_FILTER, ModuleCopyService::BLACKLIST_VCS_IGNORE_FILE]
                        );

        $moduleCopyService = new ModuleCopyService($packageService, $context, $copyGlobService);
        $moduleCopyService->copy();
    }

}
