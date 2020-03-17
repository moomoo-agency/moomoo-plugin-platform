<?php

namespace MooMoo\Platform\Bundle\KernelBundle\Templating;

use MooMoo\Platform\Bundle\KernelBundle\Kernel\Kernel;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Templating\TemplateNameParserInterface;
use Symfony\Component\Templating\TemplateReference;
use Symfony\Component\Templating\TemplateReferenceInterface;

class TemplateNameParser implements TemplateNameParserInterface
{
    /**
     * @var Kernel
     */
    private $kernel;

    /**
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->kernel = $container->get('kernel');
    }
    
    /**
     * {@inheritdoc}
     */
    public function parse($name)
    {
        if ($name instanceof TemplateReferenceInterface) {
            return $name;
        }

        $engine = null;
        if (false !== $pos = strrpos($name, '.')) {
            $engine = substr($name, $pos + 1);
        }

        $bundleName = null;
        if (false !== $pos = strrpos($name, ':')) {
            $bundleName = substr($name, 0, $pos);
            $name = substr($name, $pos + 1);
        }
        if ($bundleName) {
            if ($bundle = $this->kernel->getBundle($bundleName)) {
                $name = sprintf('%s/Resources/views/%s', $bundle->getPath(), $name);
            }
        }

        return new TemplateReference($name, $engine);
    }
}
