<?php

namespace MooMoo\Platform\Bundle\RestApiBundle;

use MooMoo\Platform\Bundle\KernelBundle\Bundle\Bundle;
use MooMoo\Platform\Bundle\KernelBundle\DependencyInjection\CompilerPass\KernelCompilerPass;
use MooMoo\Platform\Bundle\RestApiBundle\Controller\Registrator\RestApiControllersRegistratorInterface;
use MooMoo\Platform\Bundle\RestApiBundle\Controller\Registry\RestApiControllersRegistryInterface;
use MooMoo\Platform\Bundle\RestApiBundle\Endpoint\Registrator\RestApiEndpointsRegistratorInterface;
use MooMoo\Platform\Bundle\RestApiBundle\Endpoint\Registry\RestApiEndpointsRegistryInterface;
use MooMoo\Platform\Bundle\RestApiBundle\Field\Registrator\RestApiFieldsRegistratorInterface;
use MooMoo\Platform\Bundle\RestApiBundle\Field\Registry\RestApiFieldProvidersRegistryInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class RestApiBundle extends Bundle
{
    /**
     * @inheritDoc
     */
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $container->addCompilerPass(
            new KernelCompilerPass(
                'moomoo_rest_endpoint',
                'moomoo_rest_api.registry.rest_api.endpoints',
                'addEndpoint'
            )
        );
        $container->addCompilerPass(
            new KernelCompilerPass(
                'moomoo_rest_controller',
                'moomoo_rest_api.registry.rest_api.controllers',
                'addController'
            )
        );
        $container->addCompilerPass(
            new KernelCompilerPass(
                'moomoo_rest_field_provider',
                'moomoo_rest_api.registry.rest_api.field_providers',
                'addProvider'
            )
        );
    }

    /**
     * @inheritDoc
     */
    public function boot()
    {
        /** @var RestApiEndpointsRegistryInterface $restEndpointsRegistry */
        $restEndpointsRegistry = $this->container->get('moomoo_rest_api.registry.rest_api.endpoints');
        /** @var RestApiEndpointsRegistratorInterface $restEndpointsRegistrator */
        $restEndpointsRegistrator = $this->container->get('moomoo_rest_api.registrator.rest_api.endpoints');
        $restEndpointsRegistrator->registerRestEndpoints($restEndpointsRegistry->getEndpoints());

        /** @var RestApiControllersRegistryInterface $restControllersRegistry */
        $restControllersRegistry = $this->container->get('moomoo_rest_api.registry.rest_api.controllers');
        /** @var RestApiControllersRegistratorInterface $restControllersRegistrator */
        $restControllersRegistrator = $this->container->get('moomoo_rest_api.registrator.rest_api.controllers');
        $restControllersRegistrator->registerRestControllers($restControllersRegistry->getControllers());

        /** @var RestApiFieldProvidersRegistryInterface $restFieldProvidersRegistry */
        $restFieldProvidersRegistry = $this->container->get('moomoo_rest_api.registry.rest_api.field_providers');
        /** @var RestApiFieldsRegistratorInterface $restFieldsRegistrator */
        $restFieldsRegistrator = $this->container->get('moomoo_rest_api.registrator.rest_api.fields');
        $restFieldsRegistrator->registerFields($restFieldProvidersRegistry->getRestApiFieldProviders());

        parent::boot();
    }
}
