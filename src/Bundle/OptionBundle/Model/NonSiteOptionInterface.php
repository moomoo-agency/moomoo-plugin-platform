<?php

namespace MooMoo\Platform\Bundle\OptionBundle\Model;

interface NonSiteOptionInterface extends OptionInterface
{
    /**
     * @return string
     */
    public function getDeprecated();

    /**
     * @param string $deprecated
     * @return $this
     */
    public function setDeprecated($deprecated);

    /**
     * @return boolean
     */
    public function isAutoload();

    /**
     * @param boolean $autoload
     * @return $this
     */
    public function setAutoload($autoload);
}