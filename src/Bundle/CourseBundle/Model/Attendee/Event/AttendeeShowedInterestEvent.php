<?php

namespace Sprungbrett\Bundle\CourseBundle\Model\Attendee\Event;

use Sprungbrett\Bundle\CourseBundle\Model\Attendee\CourseAttendeeInterface;
use Sprungbrett\Bundle\CourseBundle\Model\Course\CourseInterface;

class AttendeeShowedInterestEvent extends AttendeeEvent
{
    const NAME = 'showed-interest';

    /**
     * @var CourseInterface
     */
    private $course;

    /**
     * @var CourseAttendeeInterface
     */
    private $courseAttendee;

    public function __construct(CourseAttendeeInterface $courseAttendee)
    {
        parent::__construct($courseAttendee->getAttendee());

        $this->courseAttendee = $courseAttendee;
        $this->course = $courseAttendee->getCourse();
    }

    public function getCourse(): CourseInterface
    {
        return $this->course;
    }

    public function getCourseAttendee(): CourseAttendeeInterface
    {
        return $this->courseAttendee;
    }
}
