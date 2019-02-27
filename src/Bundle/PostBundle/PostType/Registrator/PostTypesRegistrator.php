<?php

namespace MooMoo\Platform\Bundle\PostBundle\PostType\Registrator;

class PostTypesRegistrator implements PostTypesRegistratorInterface
{
    /**
     * @inheritDoc
     */
    public function registerPostTypes(array $postTypes)
    {
        if (!is_blog_installed()) {
            return;
        }

        add_action('init', function () use ($postTypes) {
            foreach ($postTypes as $postType) {
                if (!post_type_exists($postType->getType())) {
                    register_post_type($postType->getType(), $postType->getArguments());
                }
            }
        });
    }
}
