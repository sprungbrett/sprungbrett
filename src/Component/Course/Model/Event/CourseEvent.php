<?php

namespace Sprungbrett\Component\Course\Model\Event;

use Sprungbrett\Component\Course\Model\CourseInterface;
use Symfony\Component\EventDispatcher\Event;

class CourseEvent extends Event
{
    const COMPONENT_NAME = 'course';

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
