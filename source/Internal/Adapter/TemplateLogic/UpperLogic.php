<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EshopCommunity\Internal\Adapter\TemplateLogic;

/**
 * Class UpperLogic
 *
 * @package OxidEsales\EshopCommunity\Internal\Adapter\TemplateLogic
 * @author  Jędrzej Skoczek
 */
class UpperLogic
{

    /**
     * @param string $string
     *
     * @return string
     */
    public function upper($string)
    {
        return getStr()->strtoupper($string);
    }
}
