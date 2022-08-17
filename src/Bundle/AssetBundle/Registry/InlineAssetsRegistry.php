<?php

namespace MooMoo\Platform\Bundle\AssetBundle\Registry;

use MooMoo\Platform\Bundle\AssetBundle\Model\InlineAssetInterface;

class InlineAssetsRegistry implements InlineAssetsRegistryInterface
{
    /**
     * @var InlineAssetInterface[]
     */
    private $assets = [];

    /**
     * @param InlineAssetInterface $script
     */
    public function addAsset(InlineAssetInterface $asset)
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
