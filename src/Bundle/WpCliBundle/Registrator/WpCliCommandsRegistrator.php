<?php

namespace MooMoo\Platform\Bundle\WpCliBundle\Registrator;

use MooMoo\Platform\Bundle\WpCliBundle\Model\WpCliCommandInterface;

class WpCliCommandsRegistrator implements WpCliCommandsRegistratorInterface
{
    /**
     * {@inheritdoc}
     */
    public function registerCommands(array $commands)
    {
        add_action('cli_init', function () use ($commands) {
            foreach ($commands as $command) {
                if ($command instanceof WpCliCommandInterface) {
                    \WP_CLI::add_command(
                        $command->getName(),
                        [$command, 'execute'],
                        $command->getAdditionalRegistrationParameters()
                    );
                }
            }
        });
    }
}
