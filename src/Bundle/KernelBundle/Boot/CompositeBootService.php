<?php

namespace MooMoo\Platform\Bundle\KernelBundle\Boot;

use Symfony\Component\DependencyInjection\ContainerInterface;

class CompositeBootService implements BootServiceInterface
{
    const TAG = 'moo_boot_service';

    /**
     * @var BootServiceInterface[]
     */
    private $services =[];

    /**
     * @param BootServiceInterface $service
     */
    public function addService(BootServiceInterface $service)
    {
        $this->services[] = $service;
    }

    /**
     * {@inheritdoc}
     */
    public function boot(ContainerInterface $container)
    {
        foreach ($this->services as $service) {
            $service->boot($container);
        }
    }
}
