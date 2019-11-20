<?php

namespace MooMoo\Platform\Bundle\MetaBoxBundle\Registrator;

use MooMoo\Platform\Bundle\MetaBoxBundle\Model\MetaBoxInterface;

interface MetaBoxesRegistratorInterface
{
    /**
     * @param MetaBoxInterface[] $metaBoxes
     */
    public function registerMetaBoxes(array $metaBoxes);
}
