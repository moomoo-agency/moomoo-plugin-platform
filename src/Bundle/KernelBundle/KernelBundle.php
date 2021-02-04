<?php

namespace MooMoo\Platform\Bundle\KernelBundle;

use MooMoo\Platform\Bundle\KernelBundle\Boot\BootServiceInterface;
use MooMoo\Platform\Bundle\KernelBundle\Boot\CompositeBootService;
use MooMoo\Platform\Bundle\KernelBundle\Bundle\Bundle;
use MooMoo\Platform\Bundle\KernelBundle\DependencyInjection\CompilerPass\KernelCompilerPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\EventDispatcher\DependencyInjection\RegisterListenersPass;

class KernelBundle extends Bundle
{
    /**
     * @inheritDoc
     */
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $container->addCompilerPass(
            new KernelCompilerPass(
                CompositeBootService::TAG,
                'moomoo_kernel.boot_service.composite',
                'addService'
            )
        );
        $container->addCompilerPass(
            new KernelCompilerPass(
                'twig.extension',
                'twig',
                'addExtension'
            )
        );
        $container->addCompilerPass(
            new RegisterListenersPass(
                'event_dispatcher',
                'moomoo_event_listener',
                'moomoo_event_subscriber'
            )
        );
    }

    /**
     * @inheritDoc
     */
    public function boot()
    {
        /** @var BootServiceInterface $compositeBoot */
        $compositeBoot = $this->container->get('moomoo_kernel.boot_service.composite');
        $compositeBoot->boot($this->container);

        parent::boot();
    }
}
