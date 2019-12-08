<?php

namespace MooMoo\Platform\Bundle\QueryBundle\Hooks;

use MooMoo\Platform\Bundle\HookBundle\Model\AbstractHook;

class PostNameNotInHook extends AbstractHook
{
    const QUERY_ARGUMENT = 'post_name__not_in';

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
            $names = array_filter(array_map(function ($name) use ($wpdb) {
                $sane = sanitize_title($name);
                return !empty($sane) ? $wpdb->prepare('%s', $sane) : null;
            }, $query->query[self::QUERY_ARGUMENT]));
            if (!empty($names)) {
                $where = str_replace("''", "'", sprintf(
                    "%s AND %s.post_name NOT IN ('%s')",
                    $where,
                    $wpdb->posts,
                    implode("','", $names)
                ));
            }
        }
        return $where;
    }
}
