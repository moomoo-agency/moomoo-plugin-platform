<?php

namespace MooMoo\Platform\Bundle\OptionBundle\Model;

class Option extends AbstractOption implements NonSiteOptionInterface
{
    const DEPRECATED_FIELD = 'deprecated';
    const AUTOLOAD_FIELD = 'autoload';

    /**
     * @inheritDoc
     */
    public function getDeprecated()
    {
        return $this->get(self::DEPRECATED_FIELD);
    }

    /**
     * @inheritDoc
     */
    public function setDeprecated($deprecated)
    {
        $this->set(self::DEPRECATED_FIELD, $deprecated);

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function isAutoload()
    {
        return $this->get(self::AUTOLOAD_FIELD);
    }

    /**
     * @inheritDoc
     */
    public function setAutoload($autoload)
    {
        $this->set(self::AUTOLOAD_FIELD, $autoload);

        return $this;
    }
}