<?php

namespace MooMoo\Platform\Bundle\HookBundle\Model;

abstract class AbstractFilter extends AbstractHook
{
    /**
     * @inheritDoc
     */
    public function getType()
    {
        return self::FILTER_TYPE;
    }
}