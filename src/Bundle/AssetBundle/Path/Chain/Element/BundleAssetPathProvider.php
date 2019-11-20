<?php

namespace MooMoo\Platform\Bundle\AssetBundle\Path\Chain\Element;

use MooMoo\Platform\Bundle\AssetBundle\Model\AssetInterface;

class BundleAssetPathProvider extends AbstractAssetPathProviderChainElement
{
    /**
     * @var array
     */
    private $bundles = [];

    /**
     * @param array $bundles
     */
    public function __construct(array $bundles)
    {
        $this->bundles = $bundles;
    }
    
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

        $plugins_url = untrailingslashit( plugins_url( '/') );
        $plugins_path = ABSPATH . 'wp-content/plugins';
        $assetPathParts = explode(':', $asset->getSource());

        if (isset($this->bundles[$assetPathParts[0]])) {
            $bundleFullPath = $this->bundles[$assetPathParts[0]]->getPath();
            $bundleRelativePath = explode('plugins/', $bundleFullPath)[1];

            $relativePath = sprintf(
                '%s/Resources/assets/%s/%s',
                $bundleRelativePath,
                $subFolder,
                $assetPathParts[1]
            );

            $absolutePath = sprintf(
                '%s/%s',
                $plugins_path,
                $relativePath
            );

            if (file_exists($absolutePath)) {
                return sprintf('%s/%s', $plugins_url, $relativePath);
            } elseif ($this->getSuccessor()) {
                return $this->getSuccessor()->getAssetPath($asset);
            }

            return null;
        } elseif ($this->getSuccessor()) {
            return $this->getSuccessor()->getAssetPath($asset);
        }

        return null;
    }
}
