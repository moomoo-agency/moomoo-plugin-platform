<?php

namespace MooMoo\Platform\Bundle\AssetBundle\Model;

interface FooterTemplateScriptInterface
{
    /**
     * @return string
     */
    public function getType();

    /**
     * @return string
     */
    public function getTemplatePath();
    
    /**
     * @return string
     */
    public function getPrefix();
    
    /**
     * @return string
     */
    public function getSuffix();
    
    /**
     * @return string
     */
    public function getId();
}
