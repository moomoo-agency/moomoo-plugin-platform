<?php

namespace MooMoo\Platform\Bundle\MigrationBundle\Condition;

use MooMoo\Platform\Bundle\ConditionBundle\Model\AbstractCondition;
use MooMoo\Platform\Bundle\KernelBundle\Provider\PluginsVersionsProvider;

class LatestMigrationsAreLoadedCondition extends AbstractCondition
{
    /**
     * @var array
     */
    private $pluginsVersions = [];

    /**
     * @param PluginsVersionsProvider $provider
     */
    public function setPluginsVersionsProvider(PluginsVersionsProvider $provider)
    {
        $this->pluginsVersions = $provider->getPluginsVersions();
    }

    /**
     * @inheritDoc
     */
    protected function getResult()
    {
        foreach ($this->pluginsVersions as $plugin => $version) {
            $pluginLoadedMigrationsVersion = get_option(
                sprintf('%s_loaded_migrations_version', explode('/', $plugin)[0]),
                '0'
            );
            if (version_compare($version, $pluginLoadedMigrationsVersion) === 1) {
                return false;
            }
        }

        return true;
    }
}