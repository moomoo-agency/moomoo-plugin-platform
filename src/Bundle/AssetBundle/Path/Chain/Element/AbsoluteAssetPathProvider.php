<?php

namespace MooMoo\Platform\Bundle\AssetBundle\Path\Chain\Element;

use MooMoo\Platform\Bundle\AssetBundle\Model\AssetInterface;

class AbsoluteAssetPathProvider extends AbstractAssetPathProviderChainElement
{
    /**
     * {@inheritdoc}
     */
    public function getAssetPath(AssetInterface $asset)
    {
        if ($asset->getSource()) {
            return $asset->getSource();
        } elseif ($this->getSuccessor()) {
            return $this->getSuccessor()->getAssetPath($asset);
        }
        
        return null;
    }
}
