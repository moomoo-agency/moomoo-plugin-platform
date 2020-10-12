<?php

namespace MooMoo\Platform\Bundle\RestApiBundle\Endpoint\Registrator;

use MooMoo\Platform\Bundle\RestApiBundle\Endpoint\RestApiEndpointInterface;

class RestApiEndpointsRegistrator implements RestApiEndpointsRegistratorInterface
{
    /**
     * @inheritDoc
     */
    public function registerRestEndpoints(array $endpoints)
    {
        add_action( 'rest_api_init', function () use ($endpoints) {
            foreach ($endpoints as $endpoint) {
                register_rest_route(
                    $endpoint->getNamespace(),
                    $endpoint->getRoute(),
                    [
                        'methods' => $endpoint->getMethods(),
                        'callback' => [$endpoint, 'callback'],
                        'args' => $endpoint->getArguments(),
                        'permission_callback' => [$endpoint, 'permissionCallback']
                    ]
                );
            }
        });
    }
}