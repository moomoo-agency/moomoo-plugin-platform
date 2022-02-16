<?php

namespace MooMoo\Platform\Bundle\AssetBundle\Registrator\InlineAssets\Chain\Element;

class AdminStyleInlineAssetsRegistratorChainElement extends FrontendStyleInlineAssetsRegistratorChainElement
{
    /**
     * @var string
     */
    protected $assetRegistrationFunction = 'admin_head';
    /**
     * @var string
     */
    protected $registrationFunction = 'admin_enqueue_scripts';
}
