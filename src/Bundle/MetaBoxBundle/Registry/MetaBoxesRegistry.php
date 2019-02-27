<?php

namespace MooMoo\Platform\Bundle\MetaBoxBundle\Registry;

use MooMoo\Platform\Bundle\MetaBoxBundle\Model\MetaBoxInterface;

class MetaBoxesRegistry implements MetaBoxesRegistryInterface
{
    /**
     * @var MetaBoxInterface[]
     */
    private $metaBoxes = [];

    /**
     * @param MetaBoxInterface $metaBox
     */
    public function addMetaBox(MetaBoxInterface $metaBox)
    {
        $this->metaBoxes[$metaBox->getId()] = $metaBox;
    }

    /**
     * @inheritDoc
     */
    public function getMetaBoxes()
    {
        return $this->metaBoxes;
    }

    /**
     * @inheritDoc
     */
    public function getMetaBox($id)
    {
        if ($this->hasMetaBox($id)) {
            return $this->metaBoxes[$id];
        }
        
        return null;
    }

    /**
     * @inheritDoc
     */
    public function hasMetaBox($id)
    {
        return isset($this->metaBoxes[$id]);
    }
}
