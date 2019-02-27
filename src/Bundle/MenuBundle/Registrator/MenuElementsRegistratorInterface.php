<?php

namespace MooMoo\Platform\Bundle\MenuBundle\Registrator;

use MooMoo\Platform\Bundle\MenuBundle\Model\MenuElementInterface;

interface MenuElementsRegistratorInterface
{
    /**
     * @param MenuElementInterface[] $menuElements
     * @throws \Exception
     */
    public function register(array $menuElements);
}
