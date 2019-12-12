<?php

namespace MooMoo\Platform\Bundle\CronBundle\Model;

interface CronScheduleInterface
{
    /**
     * @return string
     */
    public function getName();

    /**
     * @return int
     */
    public function getInterval();

    /**
     * @return string
     */
    public function getDisplay();
}