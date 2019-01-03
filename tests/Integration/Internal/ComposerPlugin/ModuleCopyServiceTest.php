<?php declare(strict_types=1);
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EshopCommunity\Tests\Integration\Internal\ComposerPlugin;

use Composer\Package\Package;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use OxidEsales\EshopCommunity\Internal\Module\Install\ModuleCopyService;
use org\bovigo\vfs\vfsStream;
use org\bovigo\vfs\vfsStreamDirectory;

class ModuleCopyServiceTest extends TestCase
{
    /** @var vfsStreamDirectory */
    private $vfsStreamDirectory = null;

    public function setUp()
    {
        parent::setUp();
        $this->setupVfsStreamWrapper();
    }

    public function testCopy()
    {
        $this->setupModuleStructure();

        $copyService = new ModuleCopyService(vfsStream::url('root/source'), $this->getPackage());

        $copyService->copy(vfsStream::url('root/vendor/testvendor/testmodule'));

        $this->assertFileExists(vfsStream::url(
            'root/source/modules/testvendor/testmodule/metadata.php'),
            'Module was not copied'
        );
    }

    /**
     * @return Package|MockObject
     */
    private function getPackage() : Package
    {
        /** @var Package $package */
        $package = $this->getMockBuilder(Package::class)
            ->disableOriginalConstructor()
            ->setMethods(['getExtra', 'getName'])
            ->getMock();
        $package->method('getExtra')->willReturn(["target-directory" => "testvendor/testmodule"]);
        $package->method('getName')->willReturn('testvendor/testmodule');

        return $package;
    }



    private function setupVfsStreamWrapper()
    {
        if (!$this->vfsStreamDirectory) {
            $this->vfsStreamDirectory = vfsStream::setup();
        }
    }

    private function setupModuleStructure()
    {
        $structure = [
            'vendor' => [
                'testvendor' => [
                    'testmodule' => [
                        'metadata.php' => ''
                    ]
                ]
            ],
            'source' => [
                'modules' => []
            ]
        ];
        vfsStream::create($structure, $this->vfsStreamDirectory);
    }

}