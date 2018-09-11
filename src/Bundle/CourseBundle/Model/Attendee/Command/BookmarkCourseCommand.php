<?php

namespace Sprungbrett\Bundle\CourseBundle\Model\Attendee\Command;

use Sprungbrett\Component\Translation\Model\Command\LocaleTrait;

class BookmarkCourseCommand
{
    use LocaleTrait;

    /**
     * @var int
     */
    private $attendeeId;

    /**
     * @var string
     */
    private $courseId;

    public function __construct(int $attendeeId, string $courseId, string $locale)
    {
        $this->initializeLocale($locale);

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
