<?php

declare(strict_types=1);

namespace Sprungbrett\Bundle\CourseBundle\Model\Event;

use Sprungbrett\Bundle\CourseBundle\Model\CourseInterface;

class CourseModifiedEvent
{
    /**
     * @var CourseInterface
     */
    private $course;

    public function __construct(CourseInterface $course)
    {
        $this->course = $course;
    }

    public function getCourse(): CourseInterface
    {
        return $this->course;
    }
}
