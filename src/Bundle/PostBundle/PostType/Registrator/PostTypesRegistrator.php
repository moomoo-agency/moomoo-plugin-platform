<?php

namespace MooMoo\Platform\Bundle\PostBundle\PostType\Registrator;

use MooMoo\Platform\Bundle\ConditionBundle\Model\ConditionAwareInterface;
use MooMoo\Platform\Bundle\PostBundle\PostType\PostTypeInterface;

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
                if ($postType instanceof ConditionAwareInterface && $postType->hasConditions()) {
                    $evaluated = true;
                    foreach ($postType->getConditions() as $condition) {
                        if ($condition->evaluate() === false) {
                            $evaluated = false;
                            break;
                        }
                    }
                    if (!$evaluated) {
                        continue;
                    }
                    $this->registerPostType($postType);
                } else {
                    $this->registerPostType($postType);
                }
            }
        });
    }

    /**
     * @param PostTypeInterface $postType
     */
    private function registerPostType(PostTypeInterface $postType)
    {
        if (!post_type_exists($postType->getType())) {
            register_post_type($postType->getType(), $postType->getArguments());
        }
    }
}
