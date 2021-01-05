<?php

namespace MooMoo\Platform\Bundle\AssetBundle\Registrator\InlineAssets\Chain\Element;

use MooMoo\Platform\Bundle\AssetBundle\Model\InlineAssetInterface;

class StyleInlineAssetsRegistratorChainElement extends AbstractInlineAssetsRegistratorChainElement
{
    const ASSET_REGISTRATION_FUNCTION = 'wp_head';

    /**
     * @inheritDoc
     */
    public function isApplicable($assetType)
    {
        return 'style' === $assetType;
    }

    /**
     * @inheritDoc
     */
    public function enqueueDependency(InlineAssetInterface $asset)
    {
        if (!empty($asset->getDependencies())) {
            $wp_styles = wp_styles();
            foreach ($asset->getDependencies() as $dependency) {
                if (in_array($dependency, array_keys($wp_styles->registered))) {
                    $wp_styles->enqueue($dependency);
                }
            }
        }
    }

    /**
     * @inheritDoc
     */
    public function registerAsset(InlineAssetInterface $asset)
    {
        echo $this->getFinalContent($asset);
    }

    /**
     * @param InlineAssetInterface $asset
     * @return string
     */
    private function getFinalContent(InlineAssetInterface $asset)
    {
        $htmlAttributes = '';
        if (!empty($asset->getAssetData())) {
            $groupedData = [];
            foreach ($asset->getAssetData() as $dataItem) {
                $groupedData[$dataItem->getGroup()][$dataItem->getKey()] = $dataItem->getValue();
            }
            if (isset($groupedData['htmlAttributes'])) {
                $htmlAttributes = $this->generateHtmlAttributes($groupedData['htmlAttributes']);
            }
        }

        return sprintf(
            '<style%s%s%s>%s</style>',
            $asset->getTagType() ? sprintf(' type="%s"', $asset->getTagType()) : '',
            $asset->getId() ? sprintf(' id="%s"', $asset->getId()) : '',
            $htmlAttributes === '' ? '' : sprintf(' %s', $htmlAttributes),
            $asset->getContent()
        );
    }
}