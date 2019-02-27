<?php

namespace MooMoo\Platform\Bundle\RestApiBundle\Controller\Registrator;

class RestControllersRegistrator implements RestControllersRegistratorInterface
{
    /**
     * @inheritDoc
     */
    public function registerRestControllers(array $restControllers)
    {
        add_action( 'rest_api_init', function () use ($restControllers) {
            foreach ($restControllers as $restController) {
                $restController->register_routes();
            }
        });
    }
}
