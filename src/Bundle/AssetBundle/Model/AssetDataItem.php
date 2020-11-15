<?php

namespace MooMoo\Platform\Bundle\AssetBundle\Model;

use MooMoo\Platform\Bundle\KernelBundle\ParameterBag\ParameterBag;

class AssetDataItem extends ParameterBag implements AssetDataItemInterface
{
    const KEY_FIELD = 'key';
    const VALUE_FIELD = 'value';

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
    public function getValue()
    {
        return $this->get(self::VALUE_FIELD);
    }
}