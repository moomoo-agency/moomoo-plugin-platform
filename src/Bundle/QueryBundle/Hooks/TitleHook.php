<?php

namespace MooMoo\Platform\Bundle\QueryBundle\Hooks;

use MooMoo\Platform\Bundle\HookBundle\Model\AbstractFilter;

class TitleHook extends AbstractFilter
{
    const QUERY_ARGUMENT = 'title';

    /**
     * @inheritDoc
     */
    public function getFunction()
    {
        $where = func_get_arg(0);
        $query = func_get_arg(1);
        if (isset($query->query[self::QUERY_ARGUMENT])) {
            global $wpdb;
            $where = sprintf(
                "%s AND %s.post_title = '%s'",
                $where,
                $wpdb->posts,
                $query->query[self::QUERY_ARGUMENT]
            );
        }
        return $where;
    }
}
