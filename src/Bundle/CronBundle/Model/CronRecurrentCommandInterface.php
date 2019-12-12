<?php

namespace MooMoo\Platform\Bundle\CronBundle\Model;

interface CronRecurrentCommandInterface extends CronCommandInterface
{
    /**
     * @return string
     */
    public function getRecurrence();
}