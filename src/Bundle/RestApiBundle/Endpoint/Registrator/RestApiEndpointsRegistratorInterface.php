<?php

namespace MooMoo\Platform\Bundle\RestApiBundle\Endpoint\Registrator;

use MooMoo\Platform\Bundle\RestApiBundle\Endpoint\RestApiEndpointInterface;

interface RestApiEndpointsRegistratorInterface
{
    /**
     * @param RestApiEndpointInterface[] $endpoints
     */
    public function registerRestEndpoints(array $endpoints);
}