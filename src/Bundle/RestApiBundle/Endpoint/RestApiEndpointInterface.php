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
     * @return string
     */
    public function getMethods();

    /**
     * @param array $data
     * @return mixed
     */
    public function callback(array $data);

    /**
     * @return array
     */
    public function getArguments();

    /**
     * @return bool
     */
    public function permissionCallback();
}