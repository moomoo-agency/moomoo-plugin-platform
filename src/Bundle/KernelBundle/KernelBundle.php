<?php

namespace MooMoo\Platform\Bundle\KernelBundle;

use MooMoo\Platform\Bundle\KernelBundle\Boot\BootServiceInterface;
use MooMoo\Platform\Bundle\KernelBundle\Boot\CompositeBootService;
use MooMoo\Platform\Bundle\KernelBundle\Bundle\Bundle;
use MooMoo\Platform\Bundle\KernelBundle\DependencyInjection\CompilerPass\KernelCompilerPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class KernelBundle extends Bundle
{
    /**
     * {@inheritdoc}
     */
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $container->addCompilerPass(
            new KernelCompilerPass(
                CompositeBootService::TAG,
                'moo_kernel.boot_service.composite',
                'addService'
            )
        );
    }

    /**
     * {@inheritdoc}
     */
    public function boot()
    {
        /** @var BootServiceInterface $compositeBoot */
        $compositeBoot = $this->container->get('moo_kernel.boot_service.composite');
        $compositeBoot->boot($this->container);

        parent::boot();
    }
}
