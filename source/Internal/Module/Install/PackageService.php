<?php declare(strict_types=1);
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EshopCommunity\Internal\Module\Install;


/**
 * Class PackageService
 *
 * @package OxidEsales\EshopCommunity\Internal\Module\Setup\Install
 */
class PackageService implements PackageServiceInterface
{
    /** @var string $packagePath */
    private $packagePath;

    /**
     * PackageService constructor.
     *
     * @param string $packagePath
     */
    public function __construct(string $packagePath)
    {
        $this->packagePath = $packagePath;
    }

    /**
     * @return array
     */
    public function getExtra(): array
    {
        return [];
    }

    /**
     * @return string
     */
    public function getPath(): string
    {
        return $this->packagePath;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return '';
    }
}
