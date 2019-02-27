<?php

namespace MooMoo\Platform\Bundle\RequestBundle\Handler\Registrator\Chain\Element;

use MooMoo\Platform\Bundle\RequestBundle\Handler\RequestHandlerInterface;

interface RequestHandlersRegistratorChainElementInterface
{
    /**
     * @param RequestHandlerInterface $handler
     * @return bool
     */
    public function isApplicable(RequestHandlerInterface $handler);

    /**
     * @param RequestHandlerInterface $handler
     */
    public function register(RequestHandlerInterface $handler);
}
