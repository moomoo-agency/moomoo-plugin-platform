<?php

namespace MooMoo\Platform\Bundle\AssetBundle\DependencyInjection\CompilerPass;

use InvalidArgumentException;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class ScriptLocalizationsCompilerPass implements CompilerPassInterface
{
    const LOCALIZATION_TAG = 'moomoo_script_localization';
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
            $arguments = $definition->getArgument(1);
            $handle = null;
            if (isset($arguments['handle'])) {
                $handle = $arguments['handle'];
            }
            if ($handle === null) {
                throw new InvalidArgumentException(
                    sprintf(
                        'Service "%s" does not have required param "handle"',
                        $asset
                    )
                );
            }
            $this->assets[$handle] = $asset;
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
