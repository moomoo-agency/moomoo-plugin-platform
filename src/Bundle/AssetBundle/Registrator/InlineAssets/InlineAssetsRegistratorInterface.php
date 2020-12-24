<?php

namespace MooMoo\Platform\Bundle\AssetBundle\Registrator\InlineAssets;

use MooMoo\Platform\Bundle\AssetBundle\Model\InlineAssetInterface;

interface InlineAssetsRegistratorInterface
{
    /**
     * @param InlineAssetInterface[] $assets
     */
    public function registerAssets(array $assets);
}
