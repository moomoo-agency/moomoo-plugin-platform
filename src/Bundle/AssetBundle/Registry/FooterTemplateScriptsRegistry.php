<?php

namespace MooMoo\Platform\Bundle\AssetBundle\Registry;

use MooMoo\Platform\Bundle\AssetBundle\Model\FooterTemplateScriptInterface;

class FooterTemplateScriptsRegistry implements FooterTemplateScriptsRegistryInterface
{
    /**
     * @var FooterTemplateScriptInterface[]
     */
    private $scripts = [];

    /**
     * @param FooterTemplateScriptInterface $script
     */
    public function addScript(FooterTemplateScriptInterface $script)
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
