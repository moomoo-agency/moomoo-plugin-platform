<?php

namespace MooMoo\Platform\Bundle\QueryBundle\Hooks;

use MooMoo\Platform\Bundle\HookBundle\Model\AbstractHook;

class PostStatusNotInHook extends AbstractHook
{
    const QUERY_ARGUMENT = 'post_status__not_in';

    /**
     * @inheritDoc
     */
    public function getType()
    {
        return AbstractHook::FILTER_TYPE;
    }

    /**
     * @inheritDoc
     */
    public function getFunction()
    {
        $where = func_get_arg(0);
        $query = func_get_arg(1);
        if (isset($query->query[self::QUERY_ARGUMENT]) && is_array($query->query[self::QUERY_ARGUMENT])) {
            global $wpdb;
            $statuses = $query->query[self::QUERY_ARGUMENT];
            if (!empty($statuses)) {
                $where = sprintf(
                    "%s AND %s.post_status NOT IN ('%s')",
                    $where,
                    $wpdb->posts,
                    implode("','", $statuses)
                );
            }
        }
        return $where;
    }
}
