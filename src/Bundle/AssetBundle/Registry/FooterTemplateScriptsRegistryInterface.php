<?php

namespace MooMoo\Platform\Bundle\AssetBundle\Registry;

use MooMoo\Platform\Bundle\AssetBundle\Model\FooterTemplateScriptInterface;

interface FooterTemplateScriptsRegistryInterface
{
    /**
     * @return FooterTemplateScriptInterface[]
     */
    public function getScripts();
}
