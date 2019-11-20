<?php

namespace MooMoo\Platform\Bundle\MenuBundle\Model;

use MooMoo\Platform\Bundle\ConditionBundle\Model\ConditionAwareInterface;

interface MenuElementInterface extends ConditionAwareInterface
{
    /**
     * @return string
     */
    public function getIdentifier();

    /**
     * @return string
     */
    public function getTitle();

    /**
     * @return string
     */
    public function getParent();
}
