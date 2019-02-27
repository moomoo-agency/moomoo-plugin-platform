<?php

namespace MooMoo\Platform\Bundle\HookBundle\Model;

interface HookInterface
{
    const ACTION_TYPE = 'action';
    const FILTER_TYPE = 'filter';

    /**
     * @return string
     */
    public function getType();
    
    /**
     * @return string
     */
    public function getTag();

    /**
     * @return int
     */
    public function getPriority();

    /**
     * @return int
     */
    public function getAcceptedArgs();

    /**
     * @return mixed
     */
    public function getFunction();
}
