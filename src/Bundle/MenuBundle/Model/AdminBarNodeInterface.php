<?php

namespace MooMoo\Platform\Bundle\MenuBundle\Model;

use MooMoo\Platform\Bundle\ConditionBundle\Model\ConditionAwareInterface;

interface AdminBarNodeInterface extends MenuElementInterface, ConditionAwareInterface
{
    /**
     * @return string
     */
    public function getHref();

    /**
     * @return bool
     */
    public function isGroup();

    /**
     * @return array
     */
    public function getMeta();
}
