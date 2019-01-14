<?php declare(strict_types=1);
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EshopCommunity\Internal\Module\Install;

/**
 * @internal
 */
interface PackageServiceInterface
{
    /**
     * @return array Extra information in composer.json
     */
    public function getExtra() : array;

    /**
     * @return string Path to the package
     */
    public function getPath() : string;
}
