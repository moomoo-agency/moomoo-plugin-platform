<?php

namespace MooMoo\Platform\Bundle\AssetBundle\Registrator\InlineAssets\Chain\Element;

use MooMoo\Platform\Bundle\AssetBundle\Model\InlineAssetInterface;

interface InlineAssetsRegistratorChainElementInterface
{
    /**
     * @param InlineAssetInterface $asset
     * @return bool
     */
    public function isApplicable(InlineAssetInterface $asset);

    /**
     * @param InlineAssetInterface $asset
     */
    public function enqueueDependency(InlineAssetInterface $asset);

    /**
     * @param InlineAssetInterface $asset
     */
    public function register(InlineAssetInterface $asset);
}
