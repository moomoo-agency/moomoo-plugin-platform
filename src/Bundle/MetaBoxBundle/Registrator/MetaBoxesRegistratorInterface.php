<?php

namespace MooMoo\Platform\Bundle\MetaBoxBundle\Registrator;

interface MetaBoxesRegistratorInterface
{
    /**
     * @param array $metaBoxes
     * @return mixed
     */
    public function registerMetaBoxes(array $metaBoxes);
}
