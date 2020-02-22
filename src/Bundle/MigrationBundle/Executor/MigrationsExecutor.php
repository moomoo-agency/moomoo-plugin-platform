<?php

namespace MooMoo\Platform\Bundle\MigrationBundle\Executor;

use MooMoo\Platform\Bundle\MigrationBundle\Migration\Migration;
use MooMoo\Platform\Bundle\MigrationBundle\Migration\MigrationState;

class MigrationsExecutor
{
    /**
     * @var array
     */
    protected $plugins;

    /**
     * @param array $plugins
     */
    public function __construct(array $plugins)
    {
        $this->plugins = $plugins;
    }

    /**
     * Executes UP method for the given migrations
     *
     * @param MigrationState[] $migrations
     *
     * @throws \Exception if at lease one migration failed
     */
    public function executeUp(array $migrations)
    {
        global $wpdb;
        $failedMigrations = false;
        $wpdb->query( "START TRANSACTION" );
        foreach ($migrations as $item) {
            $migration = $item->getMigration();
            if (!empty($failedMigrations)) {
                error_log(sprintf('> %s - skipped', get_class($migration)));
                continue;
            }

            if ($this->executeUpMigration( $migration)) {
                $item->setSuccessful();
            } else {
                $item->setFailed();
                $failedMigrations[] = get_class($migration);
            }
        }

        if (!empty($failedMigrations)) {
            $wpdb->query( "ROLLBACK" );
            throw new \Exception(sprintf('Failed migrations: %s.', implode(', ', $failedMigrations)));
        } else {
            $wpdb->query( "COMMIT" );
            foreach ($this->plugins as $plugin) {
                $data = get_plugin_data(sprintf('%s/%s', WP_PLUGIN_DIR, $plugin));
                update_option(
                    sprintf('%s_loaded_migrations_version', explode('/', $plugin)[0]),
                    $data['Version']
                );
            }
        }
    }

    /**
     * @param Migration $migration
     *
     * @return bool
     */
    public function executeUpMigration(Migration $migration)
    {
        global $wpdb;
        $result = true;

        try {
            $migration->up($wpdb);
        } catch (\Exception $ex) {
            $result = false;
            error_log(sprintf('  ERROR: %s', $ex->getMessage()));
        }

        return $result;
    }

    /**
     * @param Migration[] $migrations
     * @return string[]
     *      key   = bundle name
     *      value = version
     */
    protected function getLatestSuccessMigrationVersions(array $migrations)
    {
        $result = [];
        foreach ($migrations as $migration) {
            if (!$migration->isSuccessful() || !$migration->getVersion()) {
                continue;
            }
            if (isset($result[$migration->getBundleName()])) {
                if (version_compare($result[$migration->getBundleName()], $migration->getVersion()) === -1) {
                    $result[$migration->getBundleName()] = $migration->getVersion();
                }
            } else {
                $result[$migration->getBundleName()] = $migration->getVersion();
            }
        }

        return $result;
    }
}
