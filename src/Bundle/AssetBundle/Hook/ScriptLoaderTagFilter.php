<?php

namespace MooMoo\Platform\Bundle\AssetBundle\Hook;

use MooMoo\Platform\Bundle\AssetBundle\Formatter\HtmlAttributesFormatter;
use MooMoo\Platform\Bundle\HookBundle\Model\AbstractFilter;

class ScriptLoaderTagFilter extends AbstractFilter
{
    /**
     * @inheritDoc
     */
    public function getFunction()
    {
        $tag = func_get_arg(0);
        $handle = func_get_arg(1);
        if ($htmlAttributes = wp_scripts()->get_data($handle, 'htmlAttributes')) {
            if (is_array($htmlAttributes) && !empty($htmlAttributes)) {
                $formattedAttributes = [];
                foreach ($htmlAttributes as $key => $value) {
                    $formattedAttributes[] = HtmlAttributesFormatter::format($key, $value);
                }
                if (!empty($formattedAttributes)) {
                    $formattedAttributes = implode(' ', $formattedAttributes);
                    $tag = preg_replace( ':(?=></script>):', " $formattedAttributes", $tag, 1 );
                }
            }
        }

        return $tag;
    }
}