<?php

namespace MooMoo\Platform\Bundle\AssetBundle\Registry;

use MooMoo\Platform\Bundle\AssetBundle\Model\AssetInterface;

class AssetsRegistry implements AssetsRegistryInterface
{
    /**
     * @var AssetInterface[]
     */
    private $assets = [];

    /**
     * @param AssetInterface $asset
     */
    public function addAsset(AssetInterface $asset)
    {
        $this->assets[$asset->getCategory()][] = $asset;
    }

    /**
     * @inheritDoc
     */
    public function getAssets($category)
    {
        return isset($this->assets[$category]) ? $this->assets[$category] : [];
    }
}
