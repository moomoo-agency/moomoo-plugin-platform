<?php

namespace MooMoo\Platform\Bundle\AssetBundle\Model;

interface InlineAssetAwareInterface
{
    /**
     * @return bool
     */
    public function hasInlineAssets();

    /**
     * @return InlineAssetInterface[]
     */
    public function getInlineAssets();

    /**
     * @param InlineAssetInterface $inlineAsset
     * @return $this
     */
    public function addInlineAsset(InlineAssetInterface $inlineAsset);

    /**
     * @param InlineAssetInterface[] $inlineAssets
     * @return $this
     */
    public function setInlineAssets(array $inlineAssets);
}
