<?php

namespace MooMoo\Platform\Bundle\AssetBundle\Path;

use MooMoo\Platform\Bundle\AssetBundle\Model\AssetInterface;

interface AssetPathProviderInterface
{
    /**
     * @param AssetInterface $asset
     * @return string|null
     */
    public function getAssetPath(AssetInterface $asset);
}
