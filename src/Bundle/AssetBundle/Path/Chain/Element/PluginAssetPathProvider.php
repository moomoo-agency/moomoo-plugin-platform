<?php

namespace MooMoo\Platform\Bundle\AssetBundle\Path\Chain\Element;

use MooMoo\Platform\Bundle\AssetBundle\Model\AssetInterface;

class PluginAssetPathProvider extends AbstractAssetPathProviderChainElement
{
    const BASE_FOLDER = 'plugins';

    /**
     * {@inheritdoc}
     */
    public function getAssetPath(AssetInterface $asset)
    {
        $assetType = $asset->getType();
        $subFolder = null;
        if ($assetType === AssetInterface::SCRIPT_TYPE) {
            $subFolder = 'js';
        } elseif ($assetType === AssetInterface::STYLE_TYPE) {
            $subFolder = 'css';
        }

        if ($subFolder === null) {
            throw new \InvalidArgumentException('Not correct asset type');
        }

        $base_url = untrailingslashit( get_site_url(null, sprintf('/wp-content/%s/', static::BASE_FOLDER)) );
        $plugins_path = sprintf('%swp-content/%s', ABSPATH, static::BASE_FOLDER );
        $assetPathParts = explode(':', $asset->getSource());

        $relativePath = sprintf(
            '%s/assets/%s/%s',
            $assetPathParts[0],
            $subFolder,
            $assetPathParts[1]
        );

        $absolutePath = sprintf(
            '%s/%s',
            $plugins_path,
            $relativePath
        );

        if (file_exists($absolutePath)) {
            return sprintf('%s/%s', $base_url, $relativePath);
        } elseif ($this->getSuccessor()) {
            return $this->getSuccessor()->getAssetPath($asset);
        }
        
        return null;
    }
}
