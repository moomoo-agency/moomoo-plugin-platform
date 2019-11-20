<?php

namespace MooMoo\Platform\Bundle\AssetBundle\Registrator\Assets;

use MooMoo\Platform\Bundle\AssetBundle\Model\AssetInterface;

interface AssetsRegistratorInterface
{
    /**
     * @param AssetInterface[] $assets
     * @return mixed
     */
    public function registerAssets(array $assets);
}
