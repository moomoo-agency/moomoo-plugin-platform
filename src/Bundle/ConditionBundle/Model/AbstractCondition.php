<?php

namespace MooMoo\Platform\Bundle\ConditionBundle\Model;

use MooMoo\Platform\Bundle\KernelBundle\ParameterBag\ParameterBag;

abstract class AbstractCondition extends ParameterBag implements ConditionInterface
{
    const NAME_FIELD = 'name';
    const DESCRIPTION_FIELD = 'description';
    const DEPEND_ON_CONDITIONS = 'depend_on_conditions';
    const ARGUMENTS_FIELD = 'arguments';
    const LAZY_FIELD = 'lazy';

    /**
     * @var bool
     */
    protected $validResult = true;

    /**
     * @inheritDoc
     */
    public function getName()
    {
        return $this->get(self::NAME_FIELD);
    }
    
    /**
     * @inheritDoc
     */
    public function setName($name)
    {
        $this->set(self::NAME_FIELD, $name);

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function isLazy()
    {
        return $this->get(self::LAZY_FIELD);
    }

    /**
     * @inheritDoc
     */
    public function setLazy($lazy = false)
    {
        $this->set(self::LAZY_FIELD, $lazy);

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getDescription()
    {
        return $this->get(self::DESCRIPTION_FIELD);
    }

    /**
     * @inheritDoc
     */
    public function setDescription($description)
    {
        $this->set(self::DESCRIPTION_FIELD, $description);

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function addDependOnCondition(ConditionInterface $condition)
    {
        if ($condition->isLazy() !== $this->isLazy()) {
            if ($this->isLazy() === true) {
                throw new \Exception('Lazy Condition can be dependent only on Lazy Condition');
            } else {
                throw new \Exception('Not Lazy Condition can be dependent only on Not Lazy Condition');
            }
        }
        $conditions = $this->get(self::DEPEND_ON_CONDITIONS, []);
        $conditions[$condition->getName()] = $condition;
        $this->set(self::DEPEND_ON_CONDITIONS, $conditions);

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function setDependOnConditions(array $conditions)
    {
        foreach ($conditions as $condition) {
            $this->addDependOnCondition($condition);
        }

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getDependOnConditions()
    {
        return $this->get(self::DEPEND_ON_CONDITIONS, []);
    }


    /**
     * @param bool $result
     * @return $this
     */
    public function setValidResult($result)
    {
        $this->validResult = $result;
        
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function evaluate()
    {
        $dependOnResult = true;
        if (!empty($this->getDependOnConditions())) {
            foreach ($this->getDependOnConditions() as $condition) {
                $dependOnResult = $condition->evaluate();
            }
        }
        if ($dependOnResult === false) {
            return false;
        }

        return $this->validResult === (bool)$this->getResult();
    }

    /**
     * @return bool
     */
    abstract protected function getResult();
}
