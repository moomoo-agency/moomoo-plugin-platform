<?php

namespace MooMoo\Platform\Bundle\RestApiBundle\Controller\Registry;

class RestApiControllersRegistry implements RestApiControllersRegistryInterface
{
    private $restControllers = [];

    /**
     * @param \WP_REST_Controller $restController
     */
    public function addController(\WP_REST_Controller $restController)
    {
        $this->restControllers[] = $restController;
    }
    
    /**
     * @inheritDoc
     */
    public function getControllers()
    {
        return $this->restControllers;
    }
}
