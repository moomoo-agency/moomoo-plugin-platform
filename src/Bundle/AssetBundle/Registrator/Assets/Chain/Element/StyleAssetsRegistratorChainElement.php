<?php

namespace MooMoo\Platform\Bundle\AssetBundle\Registrator\Assets\Chain\Element;

use MooMoo\Platform\Bundle\AssetBundle\Model\AssetInterface;
use MooMoo\Platform\Bundle\AssetBundle\Model\StyleInterface;

class StyleAssetsRegistratorChainElement extends AbstractAssetsRegistratorChainElement
{
    /**
     * @inheritDoc
     */
    public function isApplicable(AssetInterface $asset)
    {
        return $asset instanceof StyleInterface;
    }

    /**
     * @inheritDoc
     */
    public function register(AssetInterface $asset)
    {
        /** @var StyleInterface $asset */
        wp_enqueue_style(
            $asset->getHandle(),
            $this->pathProvider->getAssetPath($asset),
            $asset->getDependencies(),
            $asset->getVersion() ?: false,
            $asset->getMedia() ?: 'all'
        );
        if (!empty($asset->getAssetData())) {
            $groupedData = [];
            foreach ($asset->getAssetData() as $dataItem) {
                $groupedData[$dataItem->getGroup()][$dataItem->getKey()] = $dataItem->getValue();
            }
            foreach ($groupedData as $group => $values) {
                wp_script_add_data($asset->getHandle(), $group, $values);
            }
        }
    }
}
