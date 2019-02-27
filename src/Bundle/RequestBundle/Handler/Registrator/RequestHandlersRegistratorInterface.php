<?php

namespace MooMoo\Platform\Bundle\RequestBundle\Handler\Registrator;

use MooMoo\Platform\Bundle\RequestBundle\Handler\RequestHandlerInterface;

interface RequestHandlersRegistratorInterface
{
    /**
     * @param RequestHandlerInterface[] $handlers
     */
    public function registerRequestHandlers(array $handlers);
}
