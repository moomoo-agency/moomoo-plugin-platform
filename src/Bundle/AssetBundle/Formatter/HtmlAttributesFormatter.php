<?php

namespace MooMoo\Platform\Bundle\AssetBundle\Formatter;

class HtmlAttributesFormatter
{
    /**
     * @param string $key
     * @param string $value
     * @return string
     */
    public static function format($key, $value)
    {
        if ($value !== null) {
            return sprintf("%s=\"%s\"", $key, self::escape($value));
        } else {
            return $key;
        }
    }

    /**
     * @param string $value
     * @return string
     */
    private static function escape($value)
    {
        return esc_html(
            str_replace(
                '"',
                '&quot;',
                str_replace(
                    "'",
                    '&#39;',
                    $value
                )
            )
        );
    }
}