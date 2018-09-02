<?php

namespace Sprungbrett\Bundle\CourseBundle\Model\Attendee\Query;

class CountAttendeeQuery
{
    /**
     * @var string
     */
    private $courseId;

    public function __construct(string $courseId)
    {
        $this->courseId = $courseId;
    }

    public function getCourseId(): string
    {
        return $this->courseId;
    }
}
