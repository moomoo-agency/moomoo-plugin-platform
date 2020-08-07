<?php

namespace MooMoo\Platform\Bundle\TaxonomyBundle\Model;

use MooMoo\Platform\Bundle\KernelBundle\ParameterBag\ParameterBag;

class TermMeta extends ParameterBag implements TermMetaInterface
{
    const KEY_FIELD = 'key';
    const VALUE_FIELD = 'value';
    const UNIQUE_FIELD = 'unique';

    /**
     * @inheritDoc
     */
    public function getKey()
    {
        return $this->get(self::KEY_FIELD);
    }

    /**
     * @inheritDoc
     */
    public function setKey($key)
    {
        $this->set(self::KEY_FIELD, $key);

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

    /**
     * @inheritDoc
     */
    public function isUnique()
    {
        return $this->get(self::UNIQUE_FIELD, false);
    }

    /**
     * @inheritDoc
     */
    public function setUnique($unique = false)
    {
        $this->set(self::UNIQUE_FIELD, $unique);

        return $this;
    }
}