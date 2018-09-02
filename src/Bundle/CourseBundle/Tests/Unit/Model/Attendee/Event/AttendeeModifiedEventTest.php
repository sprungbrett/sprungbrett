<?php

namespace Sprungbrett\Bundle\CourseBundle\Tests\Unit\Model\Attendee\Event;

use PHPUnit\Framework\TestCase;
use Sprungbrett\Bundle\CourseBundle\Model\Attendee\AttendeeInterface;
use Sprungbrett\Bundle\CourseBundle\Model\Attendee\Event\AttendeeModifiedEvent;

class AttendeeModifiedEventTest extends TestCase
{
    public function testGetAttendee()
    {
        $attendee = $this->prophesize(AttendeeInterface::class);

        $event = new AttendeeModifiedEvent($attendee->reveal());

        $this->assertEquals($attendee->reveal(), $event->getAttendee());
    }
}
