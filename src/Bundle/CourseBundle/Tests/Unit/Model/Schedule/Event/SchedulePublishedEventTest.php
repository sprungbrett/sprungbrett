<?php

declare(strict_types=1);

namespace Sprungbrett\Bundle\CourseBundle\Tests\Unit\Model\Schedule\Event;

use PHPUnit\Framework\TestCase;
use Sprungbrett\Bundle\CourseBundle\Model\Schedule\Event\SchedulePublishedEvent;

class SchedulePublishedEventTest extends TestCase
{
    /**
     * @var SchedulePublishedEvent
     */
    private $event;

    protected function setUp()
    {
        $this->event = new SchedulePublishedEvent('123-123-123', 'en');
    }

    public function testGetSchedule(): void
    {
        $this->assertEquals('123-123-123', $this->event->getUuid());
    }

    public function testGetLocale(): void
    {
        $this->assertEquals('en', $this->event->getLocale());
    }
}
