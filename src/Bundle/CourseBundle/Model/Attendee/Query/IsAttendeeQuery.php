<?php

namespace Sprungbrett\Bundle\CourseBundle\Model\Attendee\Query;

class IsAttendeeQuery
{
    /**
     * @var int
     */
    private $attendeeId;

    /**
     * @var string
     */
    private $courseId;

    public function __construct(int $attendeeId, string $courseId)
    {
        $this->attendeeId = $attendeeId;
        $this->courseId = $courseId;
    }

    public function getAttendeeId(): int
    {
        return $this->attendeeId;
    }

    public function getCourseId(): string
    {
        return $this->courseId;
    }
}
