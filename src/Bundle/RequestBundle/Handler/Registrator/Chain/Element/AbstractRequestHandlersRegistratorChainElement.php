<?php

namespace MooMoo\Platform\Bundle\RequestBundle\Handler\Registrator\Chain\Element;

use MooMoo\Platform\Bundle\RequestBundle\Handler\Registrator\RequestHandlersRegistratorInterface;

abstract class AbstractRequestHandlersRegistratorChainElement implements
    RequestHandlersRegistratorInterface,
    RequestHandlersRegistratorChainElementInterface
{
    /**
     * @var RequestHandlersRegistratorChainElementInterface|null
     */
    private $successor;

    /**
     * @inheritDoc
     */
    public function registerRequestHandlers(array $handlers)
    {
        foreach ($handlers as $handler) {
            if ($this->isApplicable($handler)) {
                $this->register($handler);
            } elseif ($this->getSuccessor() && $this->getSuccessor()->isApplicable($handler)) {
                $this->getSuccessor()->register($handler);
            } else {
                continue;
            }
        }
    }

    /**
     * @param RequestHandlersRegistratorChainElementInterface $handlerRegistrator
     */
    public function setSuccessor(RequestHandlersRegistratorChainElementInterface $handlerRegistrator)
    {
        $this->successor = $handlerRegistrator;
    }

    /**
     * @return RequestHandlersRegistratorChainElementInterface|null
     */
    protected function getSuccessor()
    {
        return $this->successor;
    }
}
