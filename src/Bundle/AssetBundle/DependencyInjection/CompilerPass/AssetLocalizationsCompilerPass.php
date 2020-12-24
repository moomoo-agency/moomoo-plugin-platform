<?php

namespace MooMoo\Platform\Bundle\AssetBundle\DependencyInjection\CompilerPass;

use InvalidArgumentException;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class AssetLocalizationsCompilerPass implements CompilerPassInterface
{
    const LOCALIZATION_TAG = 'moomoo_asset_localization';
    const ASSET_TAG = 'moomoo_asset';

    /**
     * @var array
     */
    private $assets;

    /**
     * {@inheritDoc}
     */
    public function process(ContainerBuilder $container)
    {
        $assets = $container->findTaggedServiceIds(self::ASSET_TAG);
        if (!$assets) {
            return;
        }
        foreach ($assets as $asset => $attributes) {
            $definition = $container->getDefinition($asset);
            if ('script' === $definition->getArgument(1)) {
                $this->assets[$definition->getArgument(2)] = $asset;
            }
        }

        $localizations = $container->findTaggedServiceIds(self::LOCALIZATION_TAG);
        if ($localizations === null) {
            return;
        }

        foreach ($localizations as $localization => $attributes) {
            $handle = null;
            foreach ($attributes as $attribute) {
                if (isset($attribute['handle'])) {
                    $handle = $attribute['handle'];
                    break;
                }
            }
            if ($handle === null) {
                throw new InvalidArgumentException(
                    sprintf(
                        'Tag "%s" for service "%s" does not have required param "handle"',
                        self::LOCALIZATION_TAG,
                        $localization
                    )
                );
            }
            if (isset($this->assets[$handle])) {
                $assetDefinition = $container->getDefinition($this->assets[$handle]);
                $assetDefinition->addMethodCall('addLocalization', [new Reference($localization)]);
            }
        }
    }
}
