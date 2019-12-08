<?php

namespace MooMoo\Platform\Bundle\WpCliBundle\Model;

interface WpCliCommandInterface
{
    /**
     * @return string
     */
    public function getName();

    /**
     * @param array $arguments
     * @return mixed
     */
    public function execute($arguments = []);

    /**
     * @return array
     */
    public function getAdditionalRegistrationParameters();
}
