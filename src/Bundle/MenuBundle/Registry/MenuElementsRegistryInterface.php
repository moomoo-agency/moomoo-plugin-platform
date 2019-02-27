<?php

namespace MooMoo\Platform\Bundle\MenuBundle\Registry;

use MooMoo\Platform\Bundle\MenuBundle\Model\MenuElementInterface;

interface MenuElementsRegistryInterface
{
    /**
     * @return MenuElementInterface[]
     */
    public function getMenuElements();

    /**
     * @param string $identifier
     * @return MenuElementInterface
     */
    public function getMenuElement($identifier);

    /**
     * @param string $identifier
     * @return bool
     */
    public function hasMenuElement($identifier);
}
