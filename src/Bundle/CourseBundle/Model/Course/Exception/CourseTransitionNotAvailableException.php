<?php

namespace Sprungbrett\Bundle\CourseBundle\Model\Course\Exception;

use Sprungbrett\Bundle\CourseBundle\Model\Course\CourseInterface;

class CourseTransitionNotAvailableException extends \Exception
{
    /**
     * @var CourseInterface
     */
    private $course;

    /**
     * @var string
     */
    private $transition;

    public function __construct(CourseInterface $course, string $transition)
    {
        parent::__construct(
            sprintf('Transition "%s" for course with id "%s" not available', $transition, $course->getId())
        );

        $this->course = $course;
        $this->transition = $transition;
    }

    public function getCourse(): CourseInterface
    {
        return $this->course;
    }

    public function getTransition(): string
    {
        return $this->transition;
    }
}
