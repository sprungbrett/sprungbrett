<?php

namespace Sprungbrett\Bundle\CourseBundle\Model\Attendee\Query;

class CountBookmarksQuery
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
