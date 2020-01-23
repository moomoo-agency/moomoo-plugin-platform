<?php

namespace MooMoo\Platform\Bundle\OptionBundle\Model;

use MooMoo\Platform\Bundle\KernelBundle\ParameterBag\ParameterBag;

abstract class AbstractOption extends ParameterBag implements OptionInterface
{
    const NAME_FIELD = 'name';
    const VALUE_FIELD = 'value';

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
    public function getValue()
    {
        return $this->get(self::VALUE_FIELD);
    }

    /**
     * @inheritDoc
     */
    public function setValue($value)
    {
        $this->set(self::VALUE_FIELD, $value);

        return $this;
    }
}