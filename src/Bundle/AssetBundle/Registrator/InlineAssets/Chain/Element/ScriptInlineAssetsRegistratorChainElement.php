<?php

namespace MooMoo\Platform\Bundle\AssetBundle\Registrator\InlineAssets\Chain\Element;

use MooMoo\Platform\Bundle\AssetBundle\Model\InlineAssetInterface;

class ScriptInlineAssetsRegistratorChainElement extends AbstractInlineAssetsRegistratorChainElement
{
    const ASSET_REGISTRATION_FUNCTION = 'wp_footer';

    /**
     * @inheritDoc
     */
    public function isApplicable($assetType)
    {
        return 'script' === $assetType;
    }

    /**
     * @inheritDoc
     */
    public function enqueueDependency(InlineAssetInterface $asset)
    {
        if (!empty($asset->getDependencies())) {
            $wp_scripts = wp_scripts();
            foreach ($asset->getDependencies() as $dependency) {
                if (in_array($dependency, array_keys($wp_scripts->registered))) {
                    $wp_scripts->enqueue($dependency);
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
            '<script%s%s>%s</script>',
            $type ? sprintf(' type="%s"', $type) : '',
            $id ? sprintf(' id="%s"', $id) : '',
            $content
        );
    }
}