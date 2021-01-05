<?php

namespace MooMoo\Platform\Bundle\QueryBundle\Hooks;

use MooMoo\Platform\Bundle\HookBundle\Model\AbstractFilter;

class ParentNameInHook extends AbstractFilter
{
    const QUERY_ARGUMENT = 'parent_name__in';

    /**
     * @inheritDoc
     */
    public function getFunction()
    {
        $where = func_get_arg(0);
        $query = func_get_arg(1);
        if (isset($query->query[self::QUERY_ARGUMENT]) && is_array($query->query[self::QUERY_ARGUMENT])) {
            global $wpdb;
            $names = array_filter(array_map(function ($name) use ($wpdb) {
                $sane = sanitize_title($name);
                return !empty($sane) ? $wpdb->prepare('%s', $sane) : null;
            }, $query->query[self::QUERY_ARGUMENT]));
            if (!empty($names)) {
                $where = sprintf(
                    "%s AND %s.post_name IN ('%s')",
                    $where,
                    PostParentJoinHook::PARENT_TABLE,
                    implode("','", $names)
                );
            }
        }
        return $where;
    }
}
