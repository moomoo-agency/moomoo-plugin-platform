<?php

namespace MooMoo\Platform\Bundle\AssetBundle\Registrator\InlineAssets\Chain\Element;

class AdminScriptInlineAssetsRegistratorChainElement extends FrontendScriptInlineAssetsRegistratorChainElement
{
    /**
     * @var string
     */
    protected $assetRegistrationFunction = 'admin_footer';
    /**
     * @var string
     */
    protected $registrationFunction = 'admin_enqueue_scripts';
}
