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
     * {@inheritdoc}
     */
    public function getHandlers()
    {
        return $this->handlers;
    }

    /**
     * {@inheritdoc}
     */
    public function getHandler($actionName)
    {
        if ($this->hasHandler($actionName)) {
            return $this->handlers[$actionName];
        }
        
        return null;
    }

    /**
     * {@inheritdoc}
     */
    public function hasHandler($name)
    {
        return isset($this->handlers[$name]);
    }
}
