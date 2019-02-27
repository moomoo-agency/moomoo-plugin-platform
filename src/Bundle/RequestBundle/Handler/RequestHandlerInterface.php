<?php

namespace MooMoo\Platform\Bundle\RequestBundle\Handler;

interface RequestHandlerInterface
{
    /**
     * @return string
     */
    public function getActionName();
    
    /**
     * @return bool
     */
    public function isAjax();
    
    /**
     * @return bool
     */
    public function isPrivileged();

    /**
     * @return mixed
     */
    public function handle();
}
