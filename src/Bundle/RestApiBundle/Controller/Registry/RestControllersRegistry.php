<?php

namespace MooMoo\Platform\Bundle\RestApiBundle\Controller\Registry;

class RestControllersRegistry implements RestControllersRegistryInterface
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
