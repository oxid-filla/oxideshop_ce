<?php declare(strict_types=1);
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EshopCommunity\Internal\Module\Install;

/**
 * Interface ModuleCopyServiceInterface
 *
 * @internal
 *
 * @package OxidEsales\EshopCommunity\Internal\Module\Setup\Install
 */
interface ModuleCopyServiceInterface
{

    /**
     * Copies package from vendor directory to eShop source directory
     *
     * @param string $packagePath
     */
    public function copy(string $packagePath);
}
