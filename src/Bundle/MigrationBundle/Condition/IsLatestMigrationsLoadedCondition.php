<?php

namespace MooMoo\Platform\Bundle\MigrationBundle\Condition;

use MooMoo\Platform\Bundle\ConditionBundle\Model\AbstractCondition;

class IsLatestMigrationsLoadedCondition extends AbstractCondition
{
    /**
     * @var array
     */
    private $plugins = [];

    /**
     * @param array $plugins
     */
    public function setPlugins($plugins)
    {
        $this->plugins = $plugins;
    }

    /**
     * @inheritDoc
     */
    protected function getResult()
    {
        if( !function_exists('get_plugin_data') ){
            require_once(
                sprintf('%1$swp-admin%2$sincludes%2$splugin.php', ABSPATH, DIRECTORY_SEPARATOR)
            );
        }
        foreach ($this->plugins as $plugin) {
            $data = get_plugin_data(sprintf('%s/%s', WP_PLUGIN_DIR, $plugin));
            $pluginLoadedMigrationsVersion = get_option(
                sprintf('%s_loaded_migrations_version', explode(DIRECTORY_SEPARATOR, $plugin)[0]),
                null
            );
            if (version_compare($data['Version'], $pluginLoadedMigrationsVersion) === 1) {
                return false;
            }
        }

        return true;
    }

}