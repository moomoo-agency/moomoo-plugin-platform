<?php

namespace MooMoo\Platform\Bundle\KernelBundle\EventDispatcher;

use MooMoo\Platform\Bundle\ConditionBundle\Model\ConditionAwareInterface;
use Psr\EventDispatcher\StoppableEventInterface;
use Symfony\Component\EventDispatcher\EventDispatcher as BaseEventDispatcher;

class EventDispatcher extends BaseEventDispatcher
{
    /**
     * {@inheritDoc}
     */
    protected function callListeners(iterable $listeners, string $eventName, object $event)
    {
        $stoppable = $event instanceof StoppableEventInterface;

        foreach ($listeners as $listener) {
            $listenerObject = $listener[0];
            if ($listenerObject instanceof ConditionAwareInterface && $listenerObject->hasConditions()) {
                $evaluated = true;
                foreach ($listenerObject->getConditions() as $condition) {
                    if ($condition->evaluate() === false) {
                        $evaluated = false;
                        break;
                    }
                }
                if (!$evaluated) {
                    continue;
                }
                if ($stoppable && $event->isPropagationStopped()) {
                    break;
                }
                $listener($event, $eventName, $this);
            } else {
                if ($stoppable && $event->isPropagationStopped()) {
                    break;
                }
                $listener($event, $eventName, $this);
            }
        }
    }
}