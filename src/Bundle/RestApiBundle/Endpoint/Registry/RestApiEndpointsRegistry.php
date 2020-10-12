<?php

namespace MooMoo\Platform\Bundle\RestApiBundle\Endpoint\Registry;

use MooMoo\Platform\Bundle\RestApiBundle\Endpoint\RestApiEndpointInterface;

class RestApiEndpointsRegistry implements RestApiEndpointsRegistryInterface
{
    private $endpoints = [];

    /**
     * @param RestApiEndpointInterface $endpoint
     */
    public function addEndpoint(RestApiEndpointInterface $endpoint)
    {
        $this->endpoints[] = $endpoint;
    }

    /**
     * @inheritDoc
     */
    public function getEndpoints()
    {
        return $this->endpoints;
    }
}