<?php

namespace MooMoo\Platform\Bundle\RestApiBundle\Field\Registry;

use MooMoo\Platform\Bundle\KernelBundle\Boot\BootServiceInterface;
use MooMoo\Platform\Bundle\RestApiBundle\Field\RestApiFieldProviderInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class RestApiFieldProvidersRegistry implements RestApiFieldProvidersRegistryInterface
{
    /**
     * @var RestApiFieldProviderInterface[]
     */
    private $providers = [];

    /**
     * @param RestApiFieldProviderInterface $provider
     */
    public function addProvider(RestApiFieldProviderInterface $provider)
    {
        $this->providers[] = $provider;
    }

    /**
     * @inheritDoc
     */
    public function getRestApiFieldProviders()
    {
        return $this->providers;
    }
}
