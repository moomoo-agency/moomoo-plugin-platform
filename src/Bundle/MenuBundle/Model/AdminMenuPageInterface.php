<?php

namespace MooMoo\Platform\Bundle\MenuBundle\Model;

interface AdminMenuPageInterface extends MenuElementInterface
{
    /**
     * @return string
     */
    public function getMenuSlug();
    
    /**
     * @return string
     */
    public function getPageTitle();

    /**
     * @return array
     */
    public function getCapability();

    /**
     * @return string
     */
    public function getPage();
    
    /**
     * @return string
     */
    public function getIconUrl();

    /**
     * @return int
     */
    public function getPosition();
}
