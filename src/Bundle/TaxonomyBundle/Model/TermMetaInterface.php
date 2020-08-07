<?php

namespace MooMoo\Platform\Bundle\TaxonomyBundle\Model;

interface TermMetaInterface
{
    /**
     * @return string
     */
    public function getKey();

    /**
     * @param string $key
     * @return $this
     */
    public function setKey($key);

    /**
     * @return mixed
     */
    public function getValue();

    /**
     * @param mixed $value
     * @return $this
     */
    public function setValue($value);

    /**
     * @return bool
     */
    public function isUnique();

    /**
     * @param bool $unique
     * @return $this
     */
    public function setUnique($unique = false);
}