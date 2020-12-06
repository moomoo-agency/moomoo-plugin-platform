<?php

namespace MooMoo\Platform\Bundle\AssetBundle\Registry;

use MooMoo\Platform\Bundle\AssetBundle\Model\FooterScriptInterface;

interface FooterScriptsRegistryInterface
{
    /**
     * @return FooterScriptInterface[]
     */
    public function getScripts();
}
