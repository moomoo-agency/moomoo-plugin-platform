<?php

namespace MooMoo\Platform\Bundle\OptionBundle\Registrator\Chain\Element;

use MooMoo\Platform\Bundle\OptionBundle\Model\NonSiteOptionInterface;
use MooMoo\Platform\Bundle\OptionBundle\Model\OptionInterface;

class NonSiteOptionsRegistratorChainElement extends AbstractOptionsRegistratorChainElement
{
    /**
     * @inheritDoc
     */
    public function isApplicable(OptionInterface $option)
    {
        return $option instanceof NonSiteOptionInterface;
    }

    /**
     * @inheritDoc
     */
    public function register(OptionInterface $option)
    {
        /** @var NonSiteOptionInterface $option */
        add_option(
            $option->getName(),
            $option->getValue(),
            $option->getDeprecated() ? : '',
            $option->isAutoload() ? : 'yes'
        );
    }
}
