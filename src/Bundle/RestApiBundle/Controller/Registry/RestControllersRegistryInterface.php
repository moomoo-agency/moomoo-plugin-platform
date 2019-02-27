<?php

namespace MooMoo\Platform\Bundle\RestApiBundle\Controller\Registry;

interface RestControllersRegistryInterface
{
    /**
     * @return \WP_REST_Controller[]
     */
    public function getControllers();
}
