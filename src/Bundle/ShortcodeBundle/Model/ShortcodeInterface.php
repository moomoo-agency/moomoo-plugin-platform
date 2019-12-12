<?php

namespace MooMoo\Platform\Bundle\ShortcodeBundle\Model;

interface ShortcodeInterface
{
    /**
     * @return string
     */
    public function getTag();

    /**
     * @param array $atts
     * @param string $content
     * @return string
     */
    public function callback($atts = [], $content = null);
}
