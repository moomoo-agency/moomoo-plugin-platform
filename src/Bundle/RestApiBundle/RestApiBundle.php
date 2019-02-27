<?php

namespace MooMoo\Platform\Bundle\RestApiBundle;

use MooMoo\Platform\Bundle\KernelBundle\Bundle\Bundle;
use MooMoo\Platform\Bundle\KernelBundle\DependencyInjection\CompilerPass\KernelCompilerPass;
use MooMoo\Platform\Bundle\RestApiBundle\Controller\Registrator\RestControllersRegistratorInterface;
use MooMoo\Platform\Bundle\RestApiBundle\Controller\Registry\RestControllersRegistryInterface;
use MooMoo\Platform\Bundle\RestApiBundle\Field\Registrator\RestFieldsRegistrator;
use MooMoo\Platform\Bundle\RestApiBundle\Field\Registrator\RestFieldsRegistratorInterface;
use MooMoo\Platform\Bundle\RestApiBundle\Field\Registry\RestFieldProvidersRegistryInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class RestApiBundle extends Bundle
{
    /**
     * {@inheritdoc}
     */
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $container->addCompilerPass(
            new KernelCompilerPass(
                'moo_rest_controller',
                'moo_rest_api.registry.rest_api.controllers',
                'addController'
            )
        );
        $container->addCompilerPass(
            new KernelCompilerPass(
                'moo_rest_field_provider',
                'moo_rest_api.registry.rest_api.field_providers',
                'addProvider'
            )
        );
    }

    /**
     * @inheritDoc
     */
    public function boot()
    {
        /** @var RestControllersRegistryInterface $restControllersRegistry */
        $restControllersRegistry = $this->container->get('moo_rest_api.registry.rest_api.controllers');
        /** @var RestControllersRegistratorInterface $restControllersRegistrator */
        $restControllersRegistrator = $this->container->get('moo_rest_api.registrator.rest_api.controllers');
        $restControllersRegistrator->registerRestControllers($restControllersRegistry->getControllers());

        /** @var RestFieldProvidersRegistryInterface $restFieldProvidersRegistry */
        $restFieldProvidersRegistry = $this->container->get('moo_rest_api.registry.rest_api.field_providers');
        /** @var RestFieldsRegistratorInterface $restFieldsRegistrator */
        $restFieldsRegistrator = $this->container->get('moo_rest_api.registrator.rest_api.fields');
        $restFieldsRegistrator->registerFields($restFieldProvidersRegistry->getRestFieldProviders());

        parent::boot();
    }
}
