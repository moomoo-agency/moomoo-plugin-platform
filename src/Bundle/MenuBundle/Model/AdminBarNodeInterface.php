<?php

namespace MooMoo\Platform\Bundle\MenuBundle\Model;

interface AdminBarNodeInterface extends MenuElementInterface
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
