<?php

namespace MooMoo\Platform\Bundle\AssetBundle\Registrator\Assets\Chain\Element;

use MooMoo\Platform\Bundle\AssetBundle\Model\AssetInterface;

class StyleAssetsRegistratorChainElement extends AbstractAssetsRegistratorChainElement
{
    /**
     * @inheritDoc
     */
    public function isApplicable(AssetInterface $asset)
    {
        return $asset->getType() === AssetInterface::STYLE_TYPE;
    }

    /**
     * @inheritDoc
     */
    public function register(AssetInterface $asset)
    {
        wp_enqueue_style(
            $asset->getHandle(),
            $this->pathProvider->getAssetPath($asset),
            $asset->getDependencies(),
            $asset->getVersion() ?: '1.0.0',
            $asset->getExtra() ?: 'all'
        );
        if (!empty($asset->getAssetData())) {
            foreach ($asset->getAssetData() as $dataItem) {
                wp_style_add_data($asset->getHandle(), $dataItem->getKey(), $dataItem->getValue());
            }
        }
    }
}
