<?php

namespace MooMoo\Platform\Bundle\HookBundle\Registrator\Chain\Element;

use MooMoo\Platform\Bundle\ConditionBundle\Model\ConditionAwareInterface;
use MooMoo\Platform\Bundle\HookBundle\Model\HookInterface;
use MooMoo\Platform\Bundle\HookBundle\Registrator\HooksRegistratorInterface;

abstract class AbstractHooksRegistratorChainElement implements
    HooksRegistratorInterface,
    HooksRegistratorChainElementInterface
{
    /**
     * @var HooksRegistratorChainElementInterface|null
     */
    private $successor;

    /**
     * @inheritDoc
     */
    public function registerHooks(array $hooks)
    {
        $groupedHooks = [];
        foreach ($hooks as $hook) {
            $groupedHooks[$hook->getInitHookName()][$hook->getInitHookPriority()][] = $hook;
        }
        foreach ($groupedHooks as $initHookName => $hooksByInitPriority) {
            foreach ($hooksByInitPriority as $initHookPriority => $hooks) {
                $this->registerHooksByInitHook($initHookName, $initHookPriority, $hooks);
            }
        }
    }

    /**
     * @param string $initHookName
     * @param int $initHookPriority
     * @param HookInterface[] $hooks
     */
    private function registerHooksByInitHook($initHookName, $initHookPriority, array $hooks)
    {
        add_action(
            $initHookName,
            function () use ($hooks) {
                foreach ($hooks as $hook) {
                    if ($hook instanceof ConditionAwareInterface && $hook->hasConditions()) {
                        $evaluated = true;
                        foreach ($hook->getNotLazyConditions() as $condition) {
                            if ($condition->evaluate() === false) {
                                $evaluated = false;
                                break;
                            }
                        }
                        if (!$evaluated) {
                            continue;
                        }
                        $this->registerHook($hook);
                    } else {
                        $this->registerHook($hook);
                    }
                }
            },
            $initHookPriority
        );
    }

    /**
     * @param HookInterface $hook
     */
    private function registerHook(HookInterface $hook)
    {
        if ($this->isApplicable($hook)) {
            $this->register($hook);
        } elseif ($this->getSuccessor() && $this->getSuccessor()->isApplicable($hook)) {
            $this->getSuccessor()->register($hook);
        }
    }

    /**
     * @param HooksRegistratorChainElementInterface $hookRegistrator
     */
    public function setSuccessor(HooksRegistratorChainElementInterface $hookRegistrator)
    {
        $this->successor = $hookRegistrator;
    }

    /**
     * @return HooksRegistratorChainElementInterface|null
     */
    protected function getSuccessor()
    {
        return $this->successor;
    }
}
