<?php

namespace MooMoo\Platform\Bundle\AssetBundle\Path\Chain\Element;

use InvalidArgumentException;
use MooMoo\Platform\Bundle\AssetBundle\Model\AssetInterface;
use MooMoo\Platform\Bundle\AssetBundle\Model\ScriptInterface;
use MooMoo\Platform\Bundle\AssetBundle\Model\StyleInterface;

class PluginAssetPathProvider extends AbstractAssetPathProviderChainElement
{
    const BASE_FOLDER = 'plugins';

    /**
     * @inheritDoc
     */
    public function getAssetPath(AssetInterface $asset)
    {
        $subFolder = null;
        if ($asset instanceof ScriptInterface) {
            $subFolder = 'js';
        } elseif ($asset instanceof StyleInterface) {
            $subFolder = 'css';
        }

        if ($subFolder === null) {
            throw new InvalidArgumentException('Not correct asset type');
        }

        $base_url = untrailingslashit(get_site_url(null, sprintf('/wp-content/%s/', static::BASE_FOLDER)));
        $plugins_path = sprintf('%swp-content/%s', ABSPATH, static::BASE_FOLDER);
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
