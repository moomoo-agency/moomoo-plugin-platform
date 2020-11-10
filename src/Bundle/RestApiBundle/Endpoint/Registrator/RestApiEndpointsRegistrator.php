<?php

namespace MooMoo\Platform\Bundle\RestApiBundle\Endpoint\Registrator;

class RestApiEndpointsRegistrator implements RestApiEndpointsRegistratorInterface
{
    /**
     * @inheritDoc
     */
    public function registerRestEndpoints(array $endpoints)
    {
        add_action( 'rest_api_init', function () use ($endpoints) {
            foreach ($endpoints as $endpoint) {
                $endpoint->registerRoutes();
            }
        });
    }
}