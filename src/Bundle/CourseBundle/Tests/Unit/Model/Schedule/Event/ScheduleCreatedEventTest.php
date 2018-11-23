<?php

declare(strict_types=1);

namespace Sprungbrett\Bundle\CourseBundle\Tests\Unit\Model\Schedule\Event;

use PHPUnit\Framework\TestCase;
use Sprungbrett\Bundle\CourseBundle\Model\Schedule\Event\ScheduleCreatedEvent;
use Sprungbrett\Bundle\CourseBundle\Model\Schedule\ScheduleInterface;

class ScheduleCreatedEventTest extends TestCase
{
    /**
     * @var ScheduleInterface
     */
    private $schedule;

    /**
     * @var ScheduleCreatedEvent
     */
    private $event;

    protected function setUp()
    {
        $this->schedule = $this->prophesize(ScheduleInterface::class);

        $this->event = new ScheduleCreatedEvent($this->schedule->reveal());
    }

    public function testGetSchedule(): void
    {
        $this->assertEquals($this->schedule->reveal(), $this->event->getSchedule());
    }
}
