<?php

namespace MooMoo\Platform\Bundle\RestApiBundle\Controller\Registrator;

interface RestControllersRegistratorInterface
{
    /**
     * @param \WP_REST_Controller[] $restControllers
     */
    public function registerRestControllers(array $restControllers);
}
