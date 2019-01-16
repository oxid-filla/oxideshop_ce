<?php declare(strict_types=1);
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EshopCommunity\Tests\Unit\Internal\Module\Install;

use OxidEsales\EshopCommunity\Internal\Module\Configuration\Dao\ProjectConfigurationDaoInterface;
use OxidEsales\EshopCommunity\Internal\Module\Configuration\DataObject\EnvironmentConfiguration;
use OxidEsales\EshopCommunity\Internal\Module\Configuration\DataObject\ProjectConfiguration;
use OxidEsales\EshopCommunity\Internal\Module\Configuration\DataObject\ShopConfiguration;
use OxidEsales\EshopCommunity\Internal\Module\Install\DefaultProjectConfigurationGenerator;
use OxidEsales\EshopCommunity\Internal\Utility\ContextInterface;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
class DefaultProjectConfigurationGeneratorTest extends TestCase
{
    private $environment = 'prod';
    private $shops = [1, 2, 3];

    public function testGenerateDefaultConfiguration()
    {
        $projectConfigurationDao = $this->getMockBuilder(ProjectConfigurationDaoInterface::class)->getMock();
        $projectConfigurationDao
            ->expects($this->once())
            ->method('persistConfiguration')
            ->with($this->getExpectedDefaultProjectConfiguration());

        $generator = new DefaultProjectConfigurationGenerator($projectConfigurationDao, $this->getContext());

        $generator->generate();
    }

    private function getExpectedDefaultProjectConfiguration(): ProjectConfiguration
    {
        $environmentConfiguration = new EnvironmentConfiguration();

        foreach ($this->shops as $shopId) {
            $environmentConfiguration->addShopConfiguration($shopId, new ShopConfiguration());
        }

        $projectConfiguration = new ProjectConfiguration();
        $projectConfiguration->addEnvironmentConfiguration($this->environment, $environmentConfiguration);

        return $projectConfiguration;
    }

    private function getContext(): ContextInterface
    {
        $context = $this->getMockBuilder(ContextInterface::class)->getMock();
        $context->method('getEnvironment')->willReturn($this->environment);
        $context->method('getAllShopIds')->willReturn($this->shops);

        return $context;
    }
}
