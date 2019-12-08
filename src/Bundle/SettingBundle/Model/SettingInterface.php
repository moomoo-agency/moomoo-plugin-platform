<?php

namespace MooMoo\Platform\Bundle\SettingBundle\Model;

interface SettingInterface
{
    /**
     * @return string
     */
    public function getOptionGroup();

    /**
     * @return string
     */
    public function getOptionName();
}
