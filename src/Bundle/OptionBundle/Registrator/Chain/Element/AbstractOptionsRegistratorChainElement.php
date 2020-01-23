<?php

namespace MooMoo\Platform\Bundle\OptionBundle\Registrator\Chain\Element;

use MooMoo\Platform\Bundle\ConditionBundle\Model\ConditionAwareInterface;
use MooMoo\Platform\Bundle\OptionBundle\Model\OptionInterface;
use MooMoo\Platform\Bundle\OptionBundle\Registrator\OptionsRegistratorInterface;

abstract class AbstractOptionsRegistratorChainElement implements
    OptionsRegistratorInterface,
    OptionsRegistratorChainElementInterface
{
    /**
     * @var OptionInterface[]
     */
    private $options = [];

    /**
     * @param OptionInterface $option
     * @return $this
     */
    public function addOption(OptionInterface $option)
    {
        $this->options[$option->getName()] = $option;

        return $this;
    }

    /**
     * @var OptionsRegistratorChainElementInterface|null
     */
    private $successor;

    /**
     * @inheritDoc
     */
    public function registerOptions()
    {
        $options = $this->options;
        add_action('init', function () use ($options) {
            /** @var OptionInterface $option */
            foreach ($options as $option) {
                if ($option instanceof ConditionAwareInterface && $option->hasConditions()) {
                    $evaluated = true;
                    foreach ($option->getConditions() as $condition) {
                        if ($condition->evaluate() === false) {
                            $evaluated = false;
                            break;
                        }
                    }
                    if (!$evaluated) {
                        continue;
                    }
                    $this->registerOption($option);
                } else {
                    $this->registerOption($option);
                }
            }
        });
    }

    /**
     * @param OptionInterface $option
     */
    private function registerOption(OptionInterface $option)
    {
        if ($this->isApplicable($option)) {
            $this->register($option);
        } elseif ($this->getSuccessor() && $this->getSuccessor()->isApplicable($option)) {
            $this->getSuccessor()->register($option);
        }
    }
    
    /**
     * @param OptionsRegistratorChainElementInterface $optionRegistrator
     */
    public function setSuccessor(OptionsRegistratorChainElementInterface $optionRegistrator)
    {
        $this->successor = $optionRegistrator;
    }

    /**
     * @return OptionsRegistratorChainElementInterface|null
     */
    protected function getSuccessor()
    {
        return $this->successor;
    }
}
