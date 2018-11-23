<?php

declare(strict_types=1);

namespace Sprungbrett\Bundle\CourseBundle\Tests\Unit\Model\Schedule\Event;

use PHPUnit\Framework\TestCase;
use Sprungbrett\Bundle\CourseBundle\Model\Schedule\Event\ScheduleRemovedEvent;

class ScheduleRemovedEventTest extends TestCase
{
    /**
     * @var ScheduleRemovedEvent
     */
    private $event;

    protected function setUp()
    {
        $this->event = new ScheduleRemovedEvent('123-123-123');
    }

    public function testGetSchedule(): void
    {
        $this->assertEquals('123-123-123', $this->event->getUuid());
    }
}
