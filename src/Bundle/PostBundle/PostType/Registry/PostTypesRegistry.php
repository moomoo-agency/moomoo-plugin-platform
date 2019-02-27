<?php

namespace MooMoo\Platform\Bundle\PostBundle\PostType\Registry;

use MooMoo\Platform\Bundle\PostBundle\PostType\PostTypeInterface;

class PostTypesRegistry implements PostTypesRegistryInterface
{
    /**
     * @var PostTypeInterface[]
     */
    private $postTypes = [];

    /**
     * @param PostTypeInterface $postType
     */
    public function addPostType(PostTypeInterface $postType)
    {
        $this->postTypes[$postType->getType()] = $postType;
    }

    /**
     * @inheritDoc
     */
    public function getPostTypes()
    {
        return $this->postTypes;
    }

    /**
     * @inheritDoc
     */
    public function getPostType($type)
    {
        if ($this->hasPostType($type)) {
            return $this->postTypes[$type];
        }
        
        return null;
    }

    /**
     * @inheritDoc
     */
    public function hasPostType($type)
    {
        return isset($this->postTypes[$type]);
    }
}
