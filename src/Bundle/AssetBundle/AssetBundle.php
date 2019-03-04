<?php

namespace MooMoo\Platform\Bundle\AssetBundle;

use MooMoo\Platform\Bundle\AssetBundle\DependencyInjection\CompilerPass\AssetLocalizationsCompilerPass;
use MooMoo\Platform\Bundle\AssetBundle\Model\Asset;
use MooMoo\Platform\Bundle\AssetBundle\Registrator\Assets\AssetsRegistratorInterface;
use MooMoo\Platform\Bundle\AssetBundle\Registrator\FooterTemplateScripts\FooterTemplateScriptsRegistratorInterface;
use MooMoo\Platform\Bundle\AssetBundle\Registry\AssetsRegistryInterface;
use MooMoo\Platform\Bundle\AssetBundle\Registry\FooterTemplateScriptsRegistryInterface;
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
                'moomoo_footer_template_script',
                'moomoo_asset.registry.footer_template_scripts',
                'addScript'
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
            new AssetLocalizationsCompilerPass()
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
        $frontendAssetsRegistrator->registerAssets($assetsRegistry->getAssets(Asset::FRONTEND_CATEGORY));

        /** @var AssetsRegistratorInterface $adminAssetsRegistrator */
        $adminAssetsRegistrator = $this->container->get('moomoo_asset.registrator.admin');
        $adminAssetsRegistrator->registerAssets($assetsRegistry->getAssets(Asset::ADMIN_CATEGORY));

        /** @var FooterTemplateScriptsRegistryInterface $footerTemplateScriptsRegistry */
        $footerTemplateScriptsRegistry = $this->container->get('moomoo_asset.registry.footer_template_scripts');
        /** @var FooterTemplateScriptsRegistratorInterface $footerTemplateScriptsRegistrator */
        $footerTemplateScriptsRegistrator = $this->container->get('moomoo_asset.registrator.footer_template_scripts');
        $footerTemplateScriptsRegistrator->registerScripts($footerTemplateScriptsRegistry->getScripts());
        
        parent::boot();
    }
}
