<?php

namespace MooMoo\Platform\Bundle\AssetBundle\Registrator\FooterScripts;

use MooMoo\Platform\Bundle\AssetBundle\Model\FooterScriptInterface;

interface FooterScriptsRegistratorInterface
{
    /**
     * @param FooterScriptInterface[] $scripts
     */
    public function registerScripts(array $scripts);
}
