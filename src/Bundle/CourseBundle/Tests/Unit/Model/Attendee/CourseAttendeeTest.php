<?php

namespace Sprungbrett\Bundle\CourseBundle\Tests\Unit\Model\Attendee;

use PHPUnit\Framework\TestCase;
use Sprungbrett\Bundle\CourseBundle\Model\Attendee\AttendeeInterface;
use Sprungbrett\Bundle\CourseBundle\Model\Attendee\CourseAttendee;
use Sprungbrett\Bundle\CourseBundle\Model\Attendee\CourseAttendeeInterface;
use Sprungbrett\Bundle\CourseBundle\Model\Course\CourseInterface;

class CourseAttendeeTest extends TestCase
{
    public function testGetAttendee()
    {
        $attendee = $this->prophesize(AttendeeInterface::class);
        $course = $this->prophesize(CourseInterface::class);

        $courseAttendee = new CourseAttendee($attendee->reveal(), $course->reveal());

        $this->assertEquals($attendee->reveal(), $courseAttendee->getAttendee());
    }

    public function testGetCourse()
    {
        $attendee = $this->prophesize(AttendeeInterface::class);
        $course = $this->prophesize(CourseInterface::class);

        $courseAttendee = new CourseAttendee($attendee->reveal(), $course->reveal());

        $this->assertEquals($course->reveal(), $courseAttendee->getCourse());
    }

    public function testGetWorkflowStage()
    {
        $attendee = $this->prophesize(AttendeeInterface::class);
        $course = $this->prophesize(CourseInterface::class);

        $courseAttendee = new CourseAttendee($attendee->reveal(), $course->reveal());

        $this->assertEquals(CourseAttendeeInterface::WORKFLOW_STAGE_NEW, $courseAttendee->getWorkflowStage());
    }

    public function testSetWorkflowStage()
    {
        $attendee = $this->prophesize(AttendeeInterface::class);
        $course = $this->prophesize(CourseInterface::class);

        $courseAttendee = new CourseAttendee($attendee->reveal(), $course->reveal());

        $this->assertEquals(
            $courseAttendee,
            $courseAttendee->setWorkflowStage(CourseAttendeeInterface::WORKFLOW_STAGE_INTERESTED)
        );
        $this->assertEquals(CourseAttendeeInterface::WORKFLOW_STAGE_INTERESTED, $courseAttendee->getWorkflowStage());
    }
}
