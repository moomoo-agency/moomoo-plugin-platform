<?php

namespace MooMoo\Platform\Bundle\ConditionBundle\Model;

abstract class AbstractCondition implements ConditionInterface
{
    /**
     * @var bool
     */
    protected $validResult = true;

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
        return $this->validResult === $this->getResult();
    }

    /**
     * @return bool
     */
    abstract protected function getResult();
}
