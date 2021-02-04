<?php

namespace MooMoo\Platform\Bundle\MenuBundle;

use MooMoo\Platform\Bundle\KernelBundle\Bundle\Bundle;
use MooMoo\Platform\Bundle\KernelBundle\DependencyInjection\CompilerPass\KernelCompilerPass;
use MooMoo\Platform\Bundle\MenuBundle\Registrator\MenuElementsRegistratorInterface;
use MooMoo\Platform\Bundle\MenuBundle\Registry\MenuElementsRegistryInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class MenuBundle extends Bundle
{
    /**
     * @inheritDoc
     */
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $container->addCompilerPass(
            new KernelCompilerPass(
                'moomoo_admin_menu_page',
                'moomoo_menu.registry.admin_menu_pages',
                'addMenuElement'
            )
        );
        $container->addCompilerPass(
            new KernelCompilerPass(
                'moomoo_admin_bar_node',
                'moomoo_menu.registry.admin_bar_nodes',
                'addMenuElement'
            )
        );
    }

    /**
     * @inheritDoc
     */
    public function boot()
    {
        /** @var MenuElementsRegistryInterface $adminMenuPagesRegistry */
        $adminMenuPagesRegistry = $this->container->get('moomoo_menu.registry.admin_menu_pages');
        /** @var MenuElementsRegistratorInterface $adminMenuPagesRegistrator */
        $adminMenuPagesRegistrator = $this->container->get('moomoo_menu.registrator.admin_menu_pages');
        $adminMenuPagesRegistrator->register($adminMenuPagesRegistry->getMenuElements());

        /** @var MenuElementsRegistryInterface $adminBarNodesRegistry */
        $adminBarNodesRegistry = $this->container->get('moomoo_menu.registry.admin_bar_nodes');
        /** @var MenuElementsRegistratorInterface $adminMenuPagesRegistrator */
        $adminBarNodesRegistrator = $this->container->get('moomoo_menu.registrator.admin_bar_nodes');
        $adminBarNodesRegistrator->register($adminBarNodesRegistry->getMenuElements());

        parent::boot();
    }
}
