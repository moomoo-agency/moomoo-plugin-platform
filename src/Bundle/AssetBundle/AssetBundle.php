<?php

namespace MooMoo\Platform\Bundle\AssetBundle;

use MooMoo\Platform\Bundle\AssetBundle\DependencyInjection\CompilerPass\ScriptLocalizationsCompilerPass;
use MooMoo\Platform\Bundle\AssetBundle\Model\AbstractAsset;
use MooMoo\Platform\Bundle\AssetBundle\Model\InlineAssetInterface;
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
     * @inheritDoc
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
        /** @var InlineAssetsRegistratorInterface $frontendInlineAssetsRegistrator */
        $frontendInlineAssetsRegistrator = $this->container->get('moomoo_asset.registrator.inline_assets.frontend');
        $frontendInlineAssetsRegistrator->registerAssets($inlineAssetsRegistry->getAssets(InlineAssetInterface::FRONTEND_CATEGORY));
        /** @var InlineAssetsRegistratorInterface $adminInlineAssetsRegistrator */
        $adminInlineAssetsRegistrator = $this->container->get('moomoo_asset.registrator.inline_assets.admin');
        $adminInlineAssetsRegistrator->registerAssets($inlineAssetsRegistry->getAssets(InlineAssetInterface::ADMIN_CATEGORY));

        parent::boot();
    }
}
