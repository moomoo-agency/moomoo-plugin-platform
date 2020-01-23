<?php

namespace MooMoo\Platform\Bundle\OptionBundle\Registrator\Chain\Element;

use MooMoo\Platform\Bundle\OptionBundle\Model\OptionInterface;

interface OptionsRegistratorChainElementInterface
{
    /**
     * @param OptionInterface $option
     * @return bool
     */
    public function isApplicable(OptionInterface $option);

    /**
     * @param OptionInterface $option
     */
    public function register(OptionInterface $option);
}
