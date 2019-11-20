<?php

namespace MooMoo\Platform\Bundle\RequestBundle\Handler\Registrator\Chain\Element;

use MooMoo\Platform\Bundle\RequestBundle\Handler\RequestHandlerInterface;

class AjaxRequestHandlersRegistratorChainElement extends AbstractRequestHandlersRegistratorChainElement
{
    /**
     * @inheritDoc
     */
    public function isApplicable(RequestHandlerInterface $handler)
    {
        return $handler->isAjax();
    }


    /**
     * @inheritDoc
     */
    public function register(RequestHandlerInterface $handler)
    {
        $actionName = $handler->getActionName();
        add_action(
            sprintf('wp_ajax_%s', $actionName),
            [$handler, 'handle']
        );
        if (!$handler->isPrivileged()) {
            add_action(
                sprintf('wp_ajax_nopriv_%s', $actionName),
                [$handler, 'handle']
            );
        }
        
        return null;
    }
}
