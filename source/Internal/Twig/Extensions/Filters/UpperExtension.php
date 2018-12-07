<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EshopCommunity\Internal\Twig\Extensions\Filters;

use OxidEsales\EshopCommunity\Internal\Adapter\TemplateLogic\UpperLogic;
use Twig\Extension\AbstractExtension;

/**
 * Class UpperExtension
 *
 * @package OxidEsales\EshopCommunity\Internal\Twig\Extensions\Filters
 * @author  Jędrzej Skoczek
 */
class UpperExtension extends AbstractExtension
{

    /**
     * @var UpperLogic
     */
    private $upperLogic;

    /**
     * UpperExtension constructor.
     *
     * @param UpperLogic $upperLogic
     */
    public function __construct(UpperLogic $upperLogic)
    {
        $this->upperLogic = $upperLogic;
    }

    /**
     * @return array|\Twig_Filter[]
     */
    public function getFilters()
    {
        return [new \Twig_Filter('upper', [$this, 'upper'])];
    }

    /**
     * @param string $string
     *
     * @return string
     */
    public function upper($string)
    {
        return $this->upperLogic->upper($string);
    }
}
