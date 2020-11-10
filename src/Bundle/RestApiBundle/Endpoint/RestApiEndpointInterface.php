<?php

namespace MooMoo\Platform\Bundle\RestApiBundle\Endpoint;

interface RestApiEndpointInterface
{
    /**
     * @return string
     */
    public function getNamespace();

    /**
     * @return string
     */
    public function getRoute();

    /**
     * @see register_rest_route()
     */
    public function registerRoutes();
}