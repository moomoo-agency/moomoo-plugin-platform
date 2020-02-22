<?php

namespace MooMoo\Platform\Bundle\KernelBundle\Provider;

class PluginsVersionsProvider
{
    /**
     * @var array
     */
    private $plugins = [];

    /**
     * @var array
     */
    private $pluginsVersions = [];

    /**
     * @param array $plugins
     */
    public function __construct(array $plugins)
    {
        $this->plugins = $plugins;
    }

    /**
     * @return array
     */
    public function getPluginsVersions()
    {
        if (empty($this->pluginsVersions)) {
            if (!function_exists('get_plugin_data')) {
                require_once(
                sprintf('%1$swp-admin%2$sincludes%2$splugin.php', ABSPATH, DIRECTORY_SEPARATOR)
                );
            }
            foreach ($this->plugins as $plugin) {
                $data = get_plugin_data(sprintf('%s%s%s', WP_PLUGIN_DIR, DIRECTORY_SEPARATOR, $plugin));
                $this->pluginsVersions[$plugin] = $data['Version'];
                $this->pluginsVersions[explode(DIRECTORY_SEPARATOR, $plugin)[0]] = $data['Version'];
            }
        }

        return $this->pluginsVersions;
    }

    /**
     * @param $plugin
     * @return string|null
     */
    public function getPluginVersion($plugin)
    {
        $pluginsVersions = $this->getPluginsVersions();
        if (isset($pluginsVersions[$plugin])) {
            return $pluginsVersions[$plugin];
        }

        return null;
    }
}