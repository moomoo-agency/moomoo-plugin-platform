<?php

namespace MooMoo\Platform\Bundle\AssetBundle\Registry;

use MooMoo\Platform\Bundle\AssetBundle\Model\InlineAssetInterface;

interface InlineAssetsRegistryInterface
{
    /**
     * @return InlineAssetInterface[]
     */
    public function getAssets();
}