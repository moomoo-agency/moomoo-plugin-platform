<?php

namespace MooMoo\Platform\Bundle\WpCliBundle;

use MooMoo\Platform\Bundle\KernelBundle\Bundle\Bundle;
use MooMoo\Platform\Bundle\KernelBundle\DependencyInjection\CompilerPass\KernelCompilerPass;
use MooMoo\Platform\Bundle\WpCliBundle\Registrator\WpCliCommandsRegistratorInterface;
use MooMoo\Platform\Bundle\WpCliBundle\Registry\WpCliCommandsRegistryInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class WpCliBundle extends Bundle
{
    /**
     * {@inheritdoc}
     */
    public function build(ContainerBuilder $container)
    {
        parent::build($container);
        /** @var ContainerBuilder $container */
        $container->addCompilerPass(
            new KernelCompilerPass(
                'moomoo_wpcli_command',
                'moomoo_wpcli.registry.commands',
                'addCommand'
            )
        );
    }
    
    /**
     * @inheritDoc
     */
    public function boot()
    {
        /** @var WpCliCommandsRegistryInterface $commandsRegistry */
        $commandsRegistry = $this->container->get('moomoo_wpcli.registry.commands');
        /** @var WpCliCommandsRegistratorInterface $commandsRegistrator */
        $commandsRegistrator = $this->container->get('moomoo_wpcli.registrator.commands');
        $commandsRegistrator->registerCommands($commandsRegistry->getCommands());

        parent::boot();
    }
}
