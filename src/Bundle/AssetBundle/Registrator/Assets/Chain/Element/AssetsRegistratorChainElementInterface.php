<?php

namespace MooMoo\Platform\Bundle\AssetBundle\Registrator\Assets\Chain\Element;

use MooMoo\Platform\Bundle\AssetBundle\Model\AssetInterface;

interface AssetsRegistratorChainElementInterface
{
    /**
     * @param AssetInterface $asset
     * @return bool
     */
    public function isApplicable(AssetInterface $asset);

    /**
     * @param AssetInterface $asset
     */
    public function register(AssetInterface $asset);
}
