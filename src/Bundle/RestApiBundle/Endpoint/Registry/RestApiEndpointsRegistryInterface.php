<?php

namespace MooMoo\Platform\Bundle\RestApiBundle\Endpoint\Registry;

use MooMoo\Platform\Bundle\RestApiBundle\Endpoint\RestApiEndpointInterface;

interface RestApiEndpointsRegistryInterface
{
    /**
     * @return RestApiEndpointInterface[]
     */
    public function getEndpoints();
}