<?php

namespace MooMoo\Platform\Bundle\KernelBundle\Templating\Twig;

use \Symfony\Bridge\Twig\TwigEngine as BaseTwigEngine;

class TwigEngine extends BaseTwigEngine
{
    /**
     * {@inheritdoc}
     */
    public function render($name, array $parameters = array())
    {
        $absoluteName = $this->parser->parse($name)->getLogicalName();
        $filename = substr(strrchr($absoluteName, "/"), 1);
        /** @var \Twig_Loader_Filesystem $loader */
        $loader = $this->environment->getLoader();
        $loader->setPaths([dirname($absoluteName)]);

        return $this->environment->render($filename, $parameters);
    }
}
