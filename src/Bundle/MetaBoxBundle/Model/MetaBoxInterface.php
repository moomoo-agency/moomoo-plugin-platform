<?php

namespace MooMoo\Platform\Bundle\MetaBoxBundle\Model;

interface MetaBoxInterface
{
    /**
     * @return string
     */
    public function getPostType();

    /**
     * @return string
     */
    public function getId();

    /**
     * @return string
     */
    public function getTitle();

    /**
     * @return string|array
     */
    public function getScreen();

    /**
     * @return string
     */
    public function getContext();

    /**
     * @return string
     */
    public function getPriority();

    /**
     * @return array
     */
    public function getRenderArgs();

    /**
     * @param \WP_Post $post
     */
    public function render(\WP_Post $post);

    /**
     * @param int $post_id
     */
    public function save($post_id);
}
