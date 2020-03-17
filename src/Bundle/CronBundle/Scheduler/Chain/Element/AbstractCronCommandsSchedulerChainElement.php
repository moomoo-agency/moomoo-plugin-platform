<?php

namespace MooMoo\Platform\Bundle\CronBundle\Scheduler\Chain\Element;

use MooMoo\Platform\Bundle\CronBundle\Model\CronCommandInterface;
use MooMoo\Platform\Bundle\CronBundle\Scheduler\CronCommandsSchedulerInterface;
use MooMoo\Platform\Bundle\HookBundle\Registry\HooksRegistryInterface;
use MooMoo\Platform\Bundle\KernelBundle\Bundle\BundleInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

abstract class AbstractCronCommandsSchedulerChainElement implements
    CronCommandsSchedulerInterface,
    CronCommandsSchedulerChainElementInterface
{
    /**
     * @var array
     */
    private $bundles;

    /**
     * @var HooksRegistryInterface
     */
    protected $hooksRegistry;

    /**
     * @var CronCommandsSchedulerChainElementInterface|null
     */
    private $successor;

    /**
     * @param ContainerInterface $container
     * @param HooksRegistryInterface $hooksRegistry
     */
    public function __construct(ContainerInterface $container, HooksRegistryInterface $hooksRegistry)
    {
        $this->bundles = $container->get('kernel')->getBundles();
        $this->hooksRegistry = $hooksRegistry;
    }

    /**
     * @inheritDoc
     */
    public function scheduleCommands(array $commands)
    {
        if (!empty($commands)) {
            foreach ($commands as $command) {
                $commandClass = get_class($command);
                $filteredBundles = array_filter($this->bundles, function(BundleInterface $bundle) use ($commandClass) {
                    if (strpos($commandClass, $bundle->getNamespace()) !== false) {
                        return true;
                    }

                    return false;
                });
                /** @var BundleInterface $commandBundle */
                $commandBundle = reset($filteredBundles);
                $this->scheduleCommand($command, $commandBundle->getPluginName());
            }
        }
    }

    /**
     * @param CronCommandInterface $command
     * @param string $pluginBaseName
     */
    private function scheduleCommand(CronCommandInterface $command, $pluginBaseName)
    {
        if ($this->isApplicable($command)) {
            $this->schedule($command, $pluginBaseName);
        } elseif ($this->getSuccessor() && $this->getSuccessor()->isApplicable($command)) {
            $this->getSuccessor()->schedule($command, $pluginBaseName);
        }
    }

    /**
     * @param CronCommandsSchedulerChainElementInterface $successor
     */
    public function setSuccessor(CronCommandsSchedulerChainElementInterface $successor)
    {
        $this->successor = $successor;
    }

    /**
     * @return CronCommandsSchedulerChainElementInterface|null
     */
    protected function getSuccessor()
    {
        return $this->successor;
    }
}