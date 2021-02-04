<?php

namespace MooMoo\Platform\Bundle\RequestBundle\Registry;

use MooMoo\Platform\Bundle\RequestBundle\Handler\RequestHandlerInterface;

class RequestHandlersRegistry implements RequestHandlersRegistryInterface
{
    /**
     * @var RequestHandlerInterface[]
     */
    private $handlers = [];

    /**
     * @param RequestHandlerInterface $handler
     */
    public function addHandler(RequestHandlerInterface $handler)
    {
        $this->handlers[$handler->getActionName()] = $handler;
    }

    /**
     * @inheritDoc
     */
    public function getHandlers()
    {
        return $this->handlers;
    }

    /**
     * @inheritDoc
     */
    public function getHandler($actionName)
    {
        if ($this->hasHandler($actionName)) {
            return $this->handlers[$actionName];
        }
        
        return null;
    }

    /**
     * @inheritDoc
     */
    public function hasHandler($name)
    {
        return isset($this->handlers[$name]);
    }
}
