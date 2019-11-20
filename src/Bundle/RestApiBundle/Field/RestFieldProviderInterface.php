<?php

namespace MooMoo\Platform\Bundle\RestApiBundle\Field;

interface RestFieldProviderInterface
{
    /**
     * @return string
     */
    public function getObjectType();

    /**
     * @return string
     */
    public function getAttribute();

    /**
     * @param array $post
     * @return mixed|null
     */
    public function getGetCallback(array $post);
    
    /**
     * @param mixed $metaValue
     * @param \WP_Post $post
     * @return bool
     */
    public function getUpdateCallback($metaValue, \WP_Post $post);

    /**
     * @return array|null
     */
    public function getSchema();
}
