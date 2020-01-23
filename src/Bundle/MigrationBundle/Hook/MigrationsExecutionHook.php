<?php

namespace MooMoo\Platform\Bundle\MigrationBundle\Hook;

use MooMoo\Platform\Bundle\HookBundle\Model\AbstractHook;
use MooMoo\Platform\Bundle\MigrationBundle\Executor\MigrationsExecutor;
use MooMoo\Platform\Bundle\MigrationBundle\Loader\MigrationsLoader;

class MigrationsExecutionHook extends AbstractHook
{
    /**
     * @var MigrationsLoader
     */
    private $migrationsLoader;

    /**
     * @var MigrationsExecutor
     */
    private $migrationsExecutor;

    /**
     * @param MigrationsLoader $migrationsLoader
     * @return MigrationsExecutionHook
     */
    public function setLoader(MigrationsLoader $migrationsLoader)
    {
        $this->migrationsLoader = $migrationsLoader;

        return $this;
    }

    /**
     * @param MigrationsExecutor $migrationsExecutor
     * @return MigrationsExecutionHook
     */
    public function setExecutor(MigrationsExecutor $migrationsExecutor)
    {
        $this->migrationsExecutor = $migrationsExecutor;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getType()
    {
        return self::ACTION_TYPE;
    }

    /**
     * @inheritDoc
     */
    public function getFunction()
    {
        $migrations = $this->migrationsLoader->getMigrations();
        $this->migrationsExecutor->executeUp($migrations);
    }
}