<?php

namespace MooMoo\Platform\Bundle\RequestBundle;

use MooMoo\Platform\Bundle\KernelBundle\Bundle\Bundle;
use MooMoo\Platform\Bundle\KernelBundle\DependencyInjection\CompilerPass\KernelCompilerPass;
use MooMoo\Platform\Bundle\RequestBundle\Handler\Registrator\RequestHandlersRegistratorInterface;
use MooMoo\Platform\Bundle\RequestBundle\Registry\RequestHandlersRegistryInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class RequestBundle extends Bundle
{
    /**
     * @inheritDoc
     */
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $container->addCompilerPass(
            new KernelCompilerPass(
                'moomoo_request_handler',
                'moomoo_request.registry.request_handlers',
                'addHandler'
            )
        );
    }

    /**
     * @inheritDoc
     */
    public function boot()
    {
        /** @var RequestHandlersRegistryInterface $requestHandlersRegistry */
        $requestHandlersRegistry = $this->container->get('moomoo_request.registry.request_handlers');
        /** @var RequestHandlersRegistratorInterface $requestHandlersRegistrator */
        $requestHandlersRegistrator = $this->container->get('moomoo_request.handlers_registrator.main');
        $requestHandlersRegistrator->registerRequestHandlers($requestHandlersRegistry->getHandlers());

        parent::boot();
    }
}
