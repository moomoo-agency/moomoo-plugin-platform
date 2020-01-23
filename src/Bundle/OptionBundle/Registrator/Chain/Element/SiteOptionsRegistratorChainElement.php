<?php

namespace MooMoo\Platform\Bundle\OptionBundle\Registrator\Chain\Element;

use MooMoo\Platform\Bundle\OptionBundle\Model\OptionInterface;
use MooMoo\Platform\Bundle\OptionBundle\Model\SiteOptionInterface;

class SiteOptionsRegistratorChainElement extends AbstractOptionsRegistratorChainElement
{
    /**
     * @inheritDoc
     */
    public function isApplicable(OptionInterface $option)
    {
        return $option instanceof SiteOptionInterface;
    }

    /**
     * @inheritDoc
     */
    public function register(OptionInterface $option)
    {
        /** @var SiteOptionInterface $option */
        add_site_option(
            $option->getName(),
            $option->getValue()
        );
    }
}
