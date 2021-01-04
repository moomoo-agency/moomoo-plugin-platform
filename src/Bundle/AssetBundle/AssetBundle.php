<?php

namespace MooMoo\Platform\Bundle\AssetBundle;

use MooMoo\Platform\Bundle\AssetBundle\DependencyInjection\CompilerPass\ScriptLocalizationsCompilerPass;
use MooMoo\Platform\Bundle\AssetBundle\Model\AbstractAsset;
use MooMoo\Platform\Bundle\AssetBundle\Registrator\Assets\AssetsRegistratorInterface;
use MooMoo\Platform\Bundle\AssetBundle\Registrator\InlineAssets\InlineAssetsRegistratorInterface;
use MooMoo\Platform\Bundle\AssetBundle\Registry\AssetsRegistryInterface;
use MooMoo\Platform\Bundle\AssetBundle\Registry\InlineAssetsRegistryInterface;
use MooMoo\Platform\Bundle\KernelBundle\Bundle\Bundle;
use MooMoo\Platform\Bundle\KernelBundle\DependencyInjection\CompilerPass\KernelCompilerPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class AssetBundle extends Bundle
{
    /**
     * {@inheritdoc}
     */
    public function build(ContainerBuilder $container)
    {
        parent::build($container);


        $container->addCompilerPass(
            new KernelCompilerPass(
                'moomoo_inline_asset',
                'moomoo_asset.registry.inline_assets',
                'addAsset'
            )
        );

        $container->addCompilerPass(
            new KernelCompilerPass(
                'moomoo_asset',
                'moomoo_asset.registry.assets',
                'addAsset'
            )
        );
        $container->addCompilerPass(
            new ScriptLocalizationsCompilerPass()
        );
    }

    /**
     * @inheritDoc
     */
    public function boot()
    {
        /** @var AssetsRegistryInterface $assetsRegistry */
        $assetsRegistry = $this->container->get('moomoo_asset.registry.assets');

        /** @var AssetsRegistratorInterface $frontendAssetsRegistrator */
        $frontendAssetsRegistrator = $this->container->get('moomoo_asset.registrator.frontend');
        $frontendAssetsRegistrator->registerAssets($assetsRegistry->getAssets(AbstractAsset::FRONTEND_CATEGORY));

        /** @var AssetsRegistratorInterface $adminAssetsRegistrator */
        $adminAssetsRegistrator = $this->container->get('moomoo_asset.registrator.admin');
        $adminAssetsRegistrator->registerAssets($assetsRegistry->getAssets(AbstractAsset::ADMIN_CATEGORY));

        /** @var InlineAssetsRegistryInterface $inlineAssetsRegistry */
        $inlineAssetsRegistry = $this->container->get('moomoo_asset.registry.inline_assets');
        /** @var InlineAssetsRegistratorInterface $inlineAssetsRegistrator */
        $inlineAssetsRegistrator = $this->container->get('moomoo_asset.registrator.inline_assets');
        $inlineAssetsRegistrator->registerAssets($inlineAssetsRegistry->getAssets());

        parent::boot();
    }
}
