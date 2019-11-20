<?php

namespace MooMoo\Platform\Bundle\RestApiBundle\Field;

class RestMetaFieldProvider implements RestFieldProviderInterface
{
    /**
     * @var string
     */
    protected $objectType;

    /**
     * @var string
     */
    protected $attribute;

    /**
     * @param string $objectType
     * @param string $attribute
     */
    public function __construct($objectType, $attribute)
    {
        $this->objectType = $objectType;
        $this->attribute = $attribute;
    }

    /**
     * @inheritDoc
     */
    public function getObjectType()
    {
        return $this->objectType;
    }

    /**
     * @inheritDoc
     */
    public function getAttribute()
    {
        return $this->attribute;
    }

    /**
     * @inheritDoc
     */
    public function getGetCallback(array $post)
    {
        return get_post_meta($post['id'], $this->getAttribute(), true);
    }

    /**
     * @inheritDoc
     */
    public function getUpdateCallback($metaValue, \WP_Post $post)
    {
        update_post_meta($post->ID, $this->getAttribute(), $metaValue);

        return true;
    }

    /**
     * @inheritDoc
     */
    public function getSchema()
    {
        return null;
    }
}
