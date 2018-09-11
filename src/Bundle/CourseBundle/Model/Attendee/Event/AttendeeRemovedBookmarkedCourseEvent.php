<?php

namespace Sprungbrett\Bundle\CourseBundle\Model\Attendee\Event;

use Sprungbrett\Bundle\CourseBundle\Model\Attendee\AttendeeInterface;
use Sprungbrett\Bundle\CourseBundle\Model\Course\CourseInterface;

class AttendeeRemovedBookmarkedCourseEvent extends AttendeeEvent
{
    const NAME = 'remove-bookmarked-course';

    /**
     * @var CourseInterface
     */
    private $course;

    public function __construct(AttendeeInterface $attendee, CourseInterface $course)
    {
        parent::__construct($attendee);

        $this->course = $course;
    }

    public function getCourse(): CourseInterface
    {
        return $this->course;
    }
}
