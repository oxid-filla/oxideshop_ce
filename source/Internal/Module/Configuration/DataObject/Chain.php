<?php declare(strict_types=1);

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EshopCommunity\Internal\Module\Configuration\DataObject;

/**
 * @internal
 */
class Chain
{
    const CLASS_EXTENSIONS = 'classExtensions';
    /**
     * @var string
     */
    private $name;

    /**
     * @var array
     */
    private $chain = [];

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return Chain
     */
    public function setName(string $name): Chain
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return array
     */
    public function getChain(): array
    {
        return $this->chain;
    }

    /**
     * @param array $chain
     * @return Chain
     */
    public function setChain(array $chain): Chain
    {
        $this->chain = $chain;
        return $this;
    }

    /**
     * @param array $extensions
     */
    public function addExtensions(array $extensions)
    {
        foreach ($extensions as $extended => $extension) {
            $this->addExtensionToChain($extended, $extension);
        }
    }

    /**
     * @param string $extended
     * @param string $extension
     */
    private function addExtensionToChain(string $extended, string $extension)
    {
        if (array_key_exists($extended, $this->chain)) {
            array_push($this->chain[$extended], $extension);
        } else {
            $this->chain[$extended] = [$extension];
        }
    }
}
