<?php

namespace MooMoo\Platform\Bundle\MediaBundle\Model;

interface ImageSizeInterface
{
    /**
     * @return string
     */
    public function getName();

    /**
     * @return int
     */
    public function getWidth();

    /**
     * @return int
     */
    public function getHeight();
    
    /**
     * @return array|bool
     */
    public function getCrop();
}
