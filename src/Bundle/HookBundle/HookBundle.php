<?php

namespace MooMoo\Platform\Bundle\HookBundle;

use MooMoo\Platform\Bundle\HookBundle\Registrator\HooksRegistratorInterface;
use MooMoo\Platform\Bundle\HookBundle\Registry\HooksRegistryInterface;
use MooMoo\Platform\Bundle\KernelBundle\Bundle\Bundle;
use MooMoo\Platform\Bundle\KernelBundle\DependencyInjection\CompilerPass\KernelCompilerPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class HookBundle extends Bundle
{
    /**
     * {@inheritdoc}
     */
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $container->addCompilerPass(
            new KernelCompilerPass(
                'moo_hook',
                'moo_hook.registry.hooks',
                'addHook'
            )
        );
    }

    /**
     * @inheritDoc
     */
    public function boot()
    {
        /** @var HooksRegistryInterface $hooksRegistry */
        $hooksRegistry = $this->container->get('moo_hook.registry.hooks');
        /** @var HooksRegistratorInterface $hooksRegistrator */
        $hooksRegistrator = $this->container->get('moo_hook.hooks_registrator.main');
        $hooksRegistrator->registerHooks($hooksRegistry->getHooks());
        
        parent::boot();
    }
}
