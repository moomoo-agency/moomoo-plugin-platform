<?php

namespace MooMoo\Platform\Bundle\ConditionBundle\Model;

class IsPageCondition extends AbstractCondition
{
    /**
     * @var string
     */
    private $pageSlug;

    /**
     * @param string $pageSlug
     */
    public function __construct($pageSlug)
    {
        $this->pageSlug = $pageSlug;
    }

    /**
     * {@inheritdoc}
     */
    public function getResult()
    {
        return is_page($this->pageSlug);
    }
}
