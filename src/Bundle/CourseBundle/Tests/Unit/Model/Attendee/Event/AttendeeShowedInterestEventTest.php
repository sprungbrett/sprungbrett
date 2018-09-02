<?php

namespace Sprungbrett\Bundle\CourseBundle\Tests\Unit\Model\Attendee\Event;

use PHPUnit\Framework\TestCase;
use Sprungbrett\Bundle\CourseBundle\Model\Attendee\AttendeeInterface;
use Sprungbrett\Bundle\CourseBundle\Model\Attendee\CourseAttendeeInterface;
use Sprungbrett\Bundle\CourseBundle\Model\Attendee\Event\AttendeeShowedInterestEvent;
use Sprungbrett\Bundle\CourseBundle\Model\Course\CourseInterface;

class AttendeeShowedInterestEventTest extends TestCase
{
    public function testGetAttendee()
    {
        $attendee = $this->prophesize(AttendeeInterface::class);
        $course = $this->prophesize(CourseInterface::class);
        $courseAttendee = $this->prophesize(CourseAttendeeInterface::class);
        $courseAttendee->getAttendee()->willReturn($attendee->reveal());
        $courseAttendee->getCourse()->willReturn($course->reveal());

        $event = new AttendeeShowedInterestEvent($courseAttendee->reveal());

        $this->assertEquals($attendee->reveal(), $event->getAttendee());
    }

    public function testGetCourse()
    {
        $attendee = $this->prophesize(AttendeeInterface::class);
        $course = $this->prophesize(CourseInterface::class);
        $courseAttendee = $this->prophesize(CourseAttendeeInterface::class);
        $courseAttendee->getAttendee()->willReturn($attendee->reveal());
        $courseAttendee->getCourse()->willReturn($course->reveal());

        $event = new AttendeeShowedInterestEvent($courseAttendee->reveal());

        $this->assertEquals($course->reveal(), $event->getCourse());
    }

    public function testGetCourseAttendee()
    {
        $attendee = $this->prophesize(AttendeeInterface::class);
        $course = $this->prophesize(CourseInterface::class);
        $courseAttendee = $this->prophesize(CourseAttendeeInterface::class);
        $courseAttendee->getAttendee()->willReturn($attendee->reveal());
        $courseAttendee->getCourse()->willReturn($course->reveal());

        $event = new AttendeeShowedInterestEvent($courseAttendee->reveal());

        $this->assertEquals($courseAttendee->reveal(), $event->getCourseAttendee());
    }
}
