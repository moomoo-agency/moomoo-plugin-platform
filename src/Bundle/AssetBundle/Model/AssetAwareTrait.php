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
     * @return $this
     */
    public function addAsset(AssetInterface $asset)
    {
        if (!in_array($asset, $this->assets)) {
            $this->assets[] = $asset;
        }

        return $this;
    }

    /**
     * @param AssetInterface[] $assets
     * @return $this
     */
    public function setAssets(array $assets)
    {
        $this->assets = $assets;

        return $this;
    }
}
