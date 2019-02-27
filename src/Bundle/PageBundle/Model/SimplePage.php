<?php

namespace MooMoo\Platform\Bundle\PageBundle\Model;

class SimplePage extends AbstractPage
{
    /**
     * @var string
     */
    private $slug;

    /**
     * @var string
     */
    private $template;

    /**
     * @param $slug
     * @param $template
     */
    public function __construct($slug, $template)
    {
        $this->slug = $slug;
        $this->template = $template;
    }

    /**
     * {@inheritdoc}
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * {@inheritdoc}
     */
    public function render()
    {
        echo $this->templating->render($this->template);
    }
}
