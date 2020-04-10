<?php

namespace MooMoo\Platform\Bundle\AssetBundle\Model;

interface AssetAwareInterface
{
    /**
     * @return bool
     */
    public function hasAssets();

    /**
     * @return AssetInterface[]
     */
    public function getAssets();
}
