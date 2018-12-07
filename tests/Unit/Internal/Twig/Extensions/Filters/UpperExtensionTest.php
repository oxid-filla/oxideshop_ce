<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EshopCommunity\Tests\Unit\Internal\Twig\Extensions\Filters;

use OxidEsales\EshopCommunity\Internal\Adapter\TemplateLogic\UpperLogic;
use OxidEsales\EshopCommunity\Internal\Twig\Extensions\Filters\UpperExtension;
use PHPUnit\Framework\TestCase;

class UpperExtensionTest extends TestCase
{

    /**
     * @covers \OxidEsales\EshopCommunity\Internal\Twig\Extensions\Filters\UpperExtension::upper
     */
    public function testUpper()
    {
        $upperLogic = new UpperLogic();
        $upperExtension = new UpperExtension($upperLogic);
        $this->assertEquals('FOO', $upperExtension->upper('foo'));
    }
}
