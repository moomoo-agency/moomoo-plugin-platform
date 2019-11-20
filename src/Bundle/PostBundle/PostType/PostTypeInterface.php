<?php

namespace MooMoo\Platform\Bundle\PostBundle\PostType;

interface PostTypeInterface
{
    /**
     * @return string
     */
    public function getType();

    /**
     * @return array
     */
    public function getArguments();
}
