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
        echo $this->getFinalContent(
            $asset->getId(),
            $asset->getTagType(),
            $asset->getContent()
        );
    }

    /**
     * @param string $id
     * @param string $type
     * @param string $content
     * @return string
     */
    private function getFinalContent($id, $type, $content)
    {
        return sprintf(
            '<style%s%s>%s</style>',
            $type ? sprintf(' type="%s"', $type) : '',
            $id ? sprintf(' id="%s"', $id) : '',
            $content
        );
    }
}