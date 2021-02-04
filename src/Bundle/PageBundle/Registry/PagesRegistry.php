<?php

namespace MooMoo\Platform\Bundle\PageBundle\Registry;

use MooMoo\Platform\Bundle\PageBundle\Model\PageInterface;

class PagesRegistry implements PagesRegistryInterface
{
    /**
     * @var PageInterface[]
     */
    private $pages;

    /**
     * @param PageInterface $page
     */
    public function addPage(PageInterface $page)
    {
        $this->pages[$page->getSlug()] = $page;
    }

    /**
     * @inheritDoc
     */
    public function getPages()
    {
        return $this->pages;
    }

    /**
     * @inheritDoc
     */
    public function getPage($slug)
    {
        if ($this->hasPage($slug)) {
            return $this->pages[$slug];
        }

        return null;
    }

    /**
     * @inheritDoc
     */
    public function hasPage($slug)
    {
        return isset($this->pages[$slug]);
    }
}
