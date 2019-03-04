<?php

namespace MooMoo\Platform\Bundle\ConditionBundle\Model;

class IsConstantDefinedCondition extends AbstractCondition
{
    /**
     * @var string
     */
    private $constantName;

    /**
     * @param string $constantName
     */
    public function __construct($constantName)
    {
        $this->constantName = $constantName;
    }

    /**
     * @inheritDoc
     */
    protected function getResult()
    {
        if (defined($this->constantName)) {
            return true;
        }

        return false;
    }
}
