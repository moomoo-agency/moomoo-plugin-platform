<?php

namespace MooMoo\Platform\Bundle\AssetBundle\Registrator\FooterTemplateScripts;

use MooMoo\Platform\Bundle\AssetBundle\Model\FooterTemplateScriptInterface;

interface FooterTemplateScriptsRegistratorInterface
{
    /**
     * @param FooterTemplateScriptInterface[] $scripts
     */
    public function registerScripts(array $scripts);
}
