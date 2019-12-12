<?php

namespace MooMoo\Platform\Bundle\CronBundle;

use MooMoo\Platform\Bundle\CronBundle\Scheduler\CronCommandsSchedulerInterface;
use MooMoo\Platform\Bundle\CronBundle\Registry\CronCommandsRegistryInterface;
use MooMoo\Platform\Bundle\KernelBundle\Bundle\Bundle;
use MooMoo\Platform\Bundle\KernelBundle\DependencyInjection\CompilerPass\KernelCompilerPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class CronBundle extends Bundle
{
    /**
     * {@inheritdoc}
     */
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $container->addCompilerPass(
            new KernelCompilerPass(
                'moomoo_cron_schedule',
                'moomoo_cron.hook.cron_schedules_registration',
                'addCronSchedule'
            )
        );
        $container->addCompilerPass(
            new KernelCompilerPass(
                'moomoo_cron_command',
                'moomoo_cron.registry.cron_commands',
                'addCronCommand'
            )
        );
    }

    /**
     * @inheritDoc
     */
    public function boot()
    {
        /** @var CronCommandsRegistryInterface $commandsRegistry */
        $commandsRegistry = $this->container->get('moomoo_cron.registry.cron_commands');
        /** @var CronCommandsSchedulerInterface $commandsScheduler */
        $commandsScheduler = $this->container->get('moomoo_cron.scheduler.cron_commands');
        $commandsScheduler->scheduleCommands($commandsRegistry->getCronCommands());

        parent::boot();
    }
}
