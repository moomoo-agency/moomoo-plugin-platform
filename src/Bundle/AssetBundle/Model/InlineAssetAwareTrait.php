<?php

namespace MooMoo\Platform\Bundle\AssetBundle\Model;

trait InlineAssetAwareTrait
{
    /**
     * @var InlineAssetInterface[]
     */
    private $inlineAssets = [];

    /**
     * @return bool
     */
    public function hasInlineAssets()
    {
        return !empty($this->inlineAssets);
    }

    /**
     * @return InlineAssetInterface[]
     */
    public function getInlineAssets()
    {
        return $this->inlineAssets;
    }

    /**
     * @param InlineAssetInterface $inlineAsset
     * @return $this
     */
    public function addInlineAsset(InlineAssetInterface $inlineAsset)
    {
        if (!\in_array($inlineAsset, $this->inlineAssets)) {
            $this->inlineAssets[] = $inlineAsset;
        }
        return $this;
    }

    /**
     * @param InlineAssetInterface[] $inlineAssets
     * @return $this
     */
    public function setInlineAssets(array $inlineAssets)
    {
        $this->inlineAssets = $inlineAssets;
        return $this;
    }
}
