<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

use OxidEsales\EshopCommunity\Internal\Adapter\TemplateLogic\UpperLogic;
use OxidEsales\EshopCommunity\Internal\Application\ContainerFactory;

/**
 * Smarty upper modifier
 * -------------------------------------------------------------
 * Name:     upper<br>
 * Purpose:  convert string to uppercase
 * -------------------------------------------------------------
 *
 * @param string $sString String to uppercase
 *
 * @return string
 */

function smarty_modifier_oxupper($sString)
{
    /** @var UpperLogic $upperLogic */
    $upperLogic = ContainerFactory::getInstance()->getContainer()->get(UpperLogic::class);
    return $upperLogic->formatValue($sString);
}

?>
