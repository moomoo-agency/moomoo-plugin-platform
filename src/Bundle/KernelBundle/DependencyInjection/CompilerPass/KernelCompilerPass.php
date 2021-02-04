<?php

namespace MooMoo\Platform\Bundle\KernelBundle\DependencyInjection\CompilerPass;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class KernelCompilerPass implements CompilerPassInterface
{
    /**
     * @var string
     */
    private $tag;

    /**
     * @var string
     */
    private $service;
    
    /**
     * @var string
     */
    private $method;

    /**
     * @param string $tag
     * @param string $service
     * @param string $method
     * @throws \Exception
     */
    public function __construct($tag, $service, $method)
    {
        if (!$tag || !$service || !$method) {
            throw new \Exception('missing parameter');
        }
        $this->tag = $tag;
        $this->service = $service;
        $this->method = $method;
    }
    
    /**
     * @inheritDoc
     */
    public function process(ContainerBuilder $container)
    {
        if (!$container->hasDefinition($this->service)) {
            return;
        }

        $taggedServices = $container->findTaggedServiceIds($this->tag);
        if (!$taggedServices) {
            return;
        }

        $elements = new \SplPriorityQueue();

        $definition = $container->getDefinition($this->service);
        foreach ($taggedServices as $id => $tags) {
            foreach ($tags as $tag) {
                $priority = 0;
                if (array_key_exists('priority', $tag)) {
                    $priority = $tag['priority'];
                }
                $elements->insert(new Reference($id), $priority);
            }
        }

        foreach ($elements as $element) {
            $definition->addMethodCall($this->method, [$element]);
        }
    }
}
