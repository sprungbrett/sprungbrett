<?php

declare(strict_types=1);

namespace Sprungbrett\Bundle\CourseBundle\Model\Schedule\Event;

use Sprungbrett\Bundle\CourseBundle\Model\Schedule\ScheduleInterface;

class ScheduleModifiedEvent
{
    /**
     * @var ScheduleInterface
     */
    private $schedule;

    public function __construct(ScheduleInterface $schedule)
    {
        $this->schedule = $schedule;
    }

    public function getSchedule(): ScheduleInterface
    {
        return $this->schedule;
    }
}
