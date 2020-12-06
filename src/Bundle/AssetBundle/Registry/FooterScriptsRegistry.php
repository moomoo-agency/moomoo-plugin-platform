<?php

namespace MooMoo\Platform\Bundle\AssetBundle\Registry;

use MooMoo\Platform\Bundle\AssetBundle\Model\FooterScriptInterface;

class FooterScriptsRegistry implements FooterScriptsRegistryInterface
{
    /**
     * @var FooterScriptInterface[]
     */
    private $scripts = [];

    /**
     * @param FooterScriptInterface $script
     */
    public function addScript(FooterScriptInterface $script)
    {
        $this->scripts[] = $script;
    }

    /**
     * {@inheritdoc}
     */
    public function getScripts()
    {
        return $this->scripts;
    }
}
