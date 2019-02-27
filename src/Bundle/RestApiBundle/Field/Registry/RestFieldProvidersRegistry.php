<?php

namespace MooMoo\Platform\Bundle\RestApiBundle\Field\Registry;

use MooMoo\Platform\Bundle\KernelBundle\Boot\BootServiceInterface;
use MooMoo\Platform\Bundle\RestApiBundle\Field\RestFieldProviderInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class RestFieldProvidersRegistry implements RestFieldProvidersRegistryInterface
{
    /**
     * @var RestFieldProviderInterface[]
     */
    private $providers = [];

    /**
     * @param RestFieldProviderInterface $provider
     */
    public function addProvider(RestFieldProviderInterface $provider)
    {
        $this->providers[] = $provider;
    }

    /**
     * {@inheritdoc}
     */
    public function getRestFieldProviders()
    {
        return $this->providers;
    }
}
