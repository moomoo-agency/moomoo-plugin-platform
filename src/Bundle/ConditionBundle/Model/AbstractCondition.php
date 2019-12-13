<?php

namespace MooMoo\Platform\Bundle\ConditionBundle\Model;

use MooMoo\Platform\Bundle\KernelBundle\ParameterBag\ParameterBag;

abstract class AbstractCondition extends ParameterBag implements ConditionInterface
{
    const NAME_FIELD = 'name';
    const DESCRIPTION_FIELD = 'description';
    const DEPEND_ON_CONDITIONS = 'depend_on_conditions';
    const ARGUMENTS_FIELD = 'arguments';

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
        $this->set(self::DEPEND_ON_CONDITIONS, $conditions);

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
