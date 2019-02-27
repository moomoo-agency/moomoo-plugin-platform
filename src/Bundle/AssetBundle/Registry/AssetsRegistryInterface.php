<?php

namespace MooMoo\Platform\Bundle\AssetBundle\Registry;

use MooMoo\Platform\Bundle\AssetBundle\Model\AssetInterface;

interface AssetsRegistryInterface
{
    /**
     * @param string $category
     * @return AssetInterface[]
     */
    public function getAssets($category);
}
