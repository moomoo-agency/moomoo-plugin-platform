<?php

namespace MooMoo\Platform\Bundle\AssetBundle\Model;

trait AssetAwareTrait
{
    /**
     * @var AssetInterface[]
     */
    private $assets = [];
    
    /**
     * @return bool
     */
    public function hasAssets()
    {
        return !empty($this->assets);
    }

    /**
     * @return AssetInterface[]
     */
    public function getAssets()
    {
        return $this->assets;
    }

    /**
     * @param AssetInterface $asset
     */
    public function addAsset(AssetInterface $asset)
    {
        $this->assets[] = $asset;
    }
}
