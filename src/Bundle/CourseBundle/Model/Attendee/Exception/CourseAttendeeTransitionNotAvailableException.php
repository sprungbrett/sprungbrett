<?php

namespace Sprungbrett\Bundle\CourseBundle\Model\Attendee\Exception;

use Sprungbrett\Bundle\CourseBundle\Model\Attendee\CourseAttendeeInterface;

class CourseAttendeeTransitionNotAvailableException extends \Exception
{
    /**
     * @var CourseAttendeeInterface
     */
    private $courseAttendee;

    /**
     * @var string
     */
    private $transition;

    public function __construct(CourseAttendeeInterface $courseAttendee, string $transition)
    {
        parent::__construct(
            sprintf(
                'Transition "%s" for course "%s" and attendee "%s" not available',
                $transition,
                $courseAttendee->getCourse()->getId(),
                $courseAttendee->getAttendee()->getId()
            )
        );

        $this->courseAttendee = $courseAttendee;
        $this->transition = $transition;
    }

    public function getCourseAttendee(): CourseAttendeeInterface
    {
        return $this->courseAttendee;
    }

    public function getTransition(): string
    {
        return $this->transition;
    }
}
