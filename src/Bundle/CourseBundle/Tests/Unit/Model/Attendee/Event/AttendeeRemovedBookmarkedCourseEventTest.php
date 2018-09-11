<?php

namespace Sprungbrett\Bundle\CourseBundle\Tests\Unit\Model\Attendee\Event;

use PHPUnit\Framework\TestCase;
use Sprungbrett\Bundle\CourseBundle\Model\Attendee\AttendeeInterface;
use Sprungbrett\Bundle\CourseBundle\Model\Attendee\Event\AttendeeRemovedBookmarkedCourseEvent;
use Sprungbrett\Bundle\CourseBundle\Model\Course\CourseInterface;

class AttendeeRemovedBookmarkedCourseEventTest extends TestCase
{
    public function testGetAttendee()
    {
        $attendee = $this->prophesize(AttendeeInterface::class);
        $course = $this->prophesize(CourseInterface::class);

        $event = new AttendeeRemovedBookmarkedCourseEvent($attendee->reveal(), $course->reveal());

        $this->assertEquals($attendee->reveal(), $event->getAttendee());
    }

    public function testGetCourse()
    {
        $attendee = $this->prophesize(AttendeeInterface::class);
        $course = $this->prophesize(CourseInterface::class);

        $event = new AttendeeRemovedBookmarkedCourseEvent($attendee->reveal(), $course->reveal());

        $this->assertEquals($course->reveal(), $event->getCourse());
    }
}
