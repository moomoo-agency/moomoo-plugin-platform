<?php

namespace MooMoo\Platform\Bundle\PostBundle\PostStatus\Registrator;

use MooMoo\Platform\Bundle\ConditionBundle\Model\ConditionAwareInterface;
use MooMoo\Platform\Bundle\PostBundle\PostStatus\PostStatusInterface;

class PostStatusesRegistrator implements PostStatusesRegistratorInterface
{
    /**
     * @inheritDoc
     */
    public function registerPostStatuses(array $postStatuses)
    {
        if (!is_blog_installed()) {
            return;
        }

        add_action('init', function () use ($postStatuses) {
            foreach ($postStatuses as $postStatus) {
                if ($postStatus instanceof ConditionAwareInterface && $postStatus->hasConditions()) {
                    $evaluated = true;
                    foreach ($postStatus->getConditions() as $condition) {
                        if ($condition->evaluate() === false) {
                            $evaluated = false;
                            break;
                        }
                    }
                    if (!$evaluated) {
                        continue;
                    }
                    $this->registerPostStatus($postStatus);
                } else {
                    $this->registerPostStatus($postStatus);
                }
            }
        });
    }

    /**
     * @param PostStatusInterface $postStatus
     */
    private function registerPostStatus(PostStatusInterface $postStatus)
    {
        foreach ($postStatus->getPostTypes() as $postType) {
            if (in_array($postStatus->getStatus(), array_keys(get_available_post_statuses($postType)))) {
                $arguments = $postStatus->getArguments();
                $arguments['post_type'] = [$postType];
                register_post_status($postStatus->getStatus(), $arguments);
            }
        }
    }
}
