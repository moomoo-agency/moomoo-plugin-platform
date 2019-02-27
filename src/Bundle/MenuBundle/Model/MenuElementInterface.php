<?php

namespace MooMoo\Platform\Bundle\MenuBundle\Model;

interface MenuElementInterface
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
