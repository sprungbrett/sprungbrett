<?php

declare(strict_types=1);

namespace Sprungbrett\Bundle\CourseBundle\Tests\Unit\Model\Schedule\Event;

use PHPUnit\Framework\TestCase;
use Sprungbrett\Bundle\CourseBundle\Model\Schedule\Event\ScheduleModifiedEvent;
use Sprungbrett\Bundle\CourseBundle\Model\Schedule\ScheduleInterface;

class ScheduleModifiedEventTest extends TestCase
{
    /**
     * @var ScheduleInterface
     */
    private $schedule;

    /**
     * @var ScheduleModifiedEvent
     */
    private $event;

    protected function setUp()
    {
        $this->schedule = $this->prophesize(ScheduleInterface::class);

        $this->event = new ScheduleModifiedEvent($this->schedule->reveal());
    }

    public function testGetSchedule(): void
    {
        $this->assertEquals($this->schedule->reveal(), $this->event->getSchedule());
    }
}
