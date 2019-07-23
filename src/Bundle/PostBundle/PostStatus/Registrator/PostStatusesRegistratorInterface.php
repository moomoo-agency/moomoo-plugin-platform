<?php

namespace MooMoo\Platform\Bundle\PostBundle\PostStatus\Registrator;

use MooMoo\Platform\Bundle\PostBundle\PostStatus\PostStatusInterface;

interface PostStatusesRegistratorInterface
{
    /**
     * @param PostStatusInterface[] $postStatuses
     */
    public function registerPostStatuses(array $postStatuses);
}
