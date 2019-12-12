<?php

namespace MooMoo\Platform\Bundle\MenuBundle\Model;

use MooMoo\Platform\Bundle\ConditionBundle\Model\ConditionAwareTrait;
use MooMoo\Platform\Bundle\KernelBundle\ParameterBag\ParameterBag;

class AdminMenuPage extends ParameterBag implements AdminMenuPageInterface
{
    const MENU_SLUG_FIELD = 'menu_slug';
    const TITLE_FIELD = 'menu_title';
    const PARENT_FIELD = 'parent';
    const PAGE_TITLE_FIELD = 'page_title';
    const CAPABILITY_FIELD = 'capability';
    const ICON_URL_FIELD = 'icon_url';
    const POSITION_FIELD = 'position';
    const PAGE_FIELD = 'page';
    
    use ConditionAwareTrait;
    
    /**
     * @inheritDoc
     */
    public function getIdentifier()
    {
        $identifier = strtolower(str_replace(' ', '_', $this->get(self::TITLE_FIELD)));
        if ($parent = $this->getParent()) {
            $identifier = sprintf('%s_%s', $parent, $identifier);
        }
        return $identifier;
    }

    /**
     * @inheritDoc
     */
    public function getMenuSlug()
    {
        return $this->get(self::MENU_SLUG_FIELD);
    }

    /**
     * @inheritDoc
     */
    public function getTitle()
    {
        return $this->get(self::TITLE_FIELD);
    }

    /**
     * @inheritDoc
     */
    public function getParent()
    {
        return $this->get(self::PARENT_FIELD);
    }

    /**
     * @inheritDoc
     */
    public function getPageTitle()
    {
        return $this->get(self::PAGE_TITLE_FIELD);
    }

    /**
     * @inheritDoc
     */
    public function getCapability()
    {
        return $this->get(self::CAPABILITY_FIELD);
    }

    /**
     * @inheritDoc
     */
    public function getPage()
    {
        return $this->get(self::PAGE_FIELD);
    }

    /**
     * @inheritDoc
     */
    public function getIconUrl()
    {
        return $this->get(self::ICON_URL_FIELD);
    }

    /**
     * @inheritDoc
     */
    public function getPosition()
    {
        return $this->get(self::POSITION_FIELD);
    }
}
