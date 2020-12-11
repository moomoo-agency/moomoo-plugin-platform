<?php

namespace MooMoo\Platform\Bundle\KernelBundle\Templating\Twig;

use Symfony\Component\Templating\EngineInterface;
use Symfony\Component\Templating\StreamingEngineInterface;
use Symfony\Component\Templating\TemplateNameParserInterface;
use Symfony\Component\Templating\TemplateReferenceInterface;
use Twig\Environment;
use Twig\Error\Error;
use Twig\Error\LoaderError;
use Twig\Loader\FilesystemLoader;
use Twig\Template;

class TwigEngine implements EngineInterface, StreamingEngineInterface
{
    protected $environment;
    protected $parser;

    public function __construct(Environment $environment, TemplateNameParserInterface $parser)
    {
        $this->environment = $environment;
        $this->parser = $parser;
    }

    /**
     * {@inheritdoc}
     */
    public function render($name, array $parameters = array())
    {
        $absoluteName = $this->parser->parse($name)->getLogicalName();
        $filename = \substr(\strrchr($absoluteName, "/"), 1);
        /** @var FilesystemLoader $loader */
        $loader = $this->environment->getLoader();
        $loader->setPaths([\dirname($absoluteName)]);
        return $this->environment->render($filename, $parameters);
    }

    /**
     * {@inheritdoc}
     *
     * It also supports Template as name parameter.
     *
     * @throws Error if something went wrong like a thrown exception while rendering the template
     */
    public function stream($name, array $parameters = array())
    {
        $this->load($name)->display($parameters);
    }

    /**
     * {@inheritdoc}
     *
     * It also supports Template as name parameter.
     */
    public function exists($name)
    {
        if ($name instanceof Template) {
            return true;
        }

        $loader = $this->environment->getLoader();

        if ($loader instanceof ExistsLoaderInterface || method_exists($loader, 'exists')) {
            return $loader->exists((string) $name);
        }

        try {
            // cast possible TemplateReferenceInterface to string because the
            // EngineInterface supports them but LoaderInterface does not
            $loader->getSourceContext((string) $name)->getCode();
        } catch (LoaderError $e) {
            return false;
        }

        return true;
    }

    /**
     * {@inheritdoc}
     *
     * It also supports Template as name parameter.
     */
    public function supports($name)
    {
        if ($name instanceof Template) {
            return true;
        }

        $template = $this->parser->parse($name);

        return 'twig' === $template->get('engine');
    }

    /**
     * Loads the given template.
     *
     * @param string|TemplateReferenceInterface|Template $name A template name or an instance of
     *                                                         TemplateReferenceInterface or Template
     *
     * @return Template
     *
     * @throws \InvalidArgumentException if the template does not exist
     */
    protected function load($name)
    {
        if ($name instanceof Template) {
            return $name;
        }

        try {
            return $this->environment->loadTemplate((string) $name);
        } catch (LoaderError $e) {
            throw new \InvalidArgumentException($e->getMessage(), $e->getCode(), $e);
        }
    }
}
