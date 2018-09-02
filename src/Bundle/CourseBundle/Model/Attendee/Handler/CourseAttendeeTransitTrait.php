<?php

namespace Sprungbrett\Bundle\CourseBundle\Model\Attendee\Handler;

use Sprungbrett\Bundle\CourseBundle\Model\Attendee\CourseAttendeeInterface;
use Sprungbrett\Bundle\CourseBundle\Model\Attendee\Event\AttendeeEvent;
use Sprungbrett\Bundle\CourseBundle\Model\Attendee\Exception\CourseAttendeeTransitionNotAvailableException;
use Sprungbrett\Component\EventCollector\EventCollector;
use Symfony\Component\Workflow\Workflow;

trait CourseAttendeeTransitTrait
{
    /**
     * @var Workflow
     */
    protected $workflow;

    /**
     * @var EventCollector
     */
    protected $eventCollector;

    public function initializeTransit(Workflow $workflow, EventCollector $eventCollector): void
    {
        $this->workflow = $workflow;
        $this->eventCollector = $eventCollector;
    }

    protected function transition(string $transition, AttendeeEvent $event, CourseAttendeeInterface $courseAttendee): void
    {
        if (!$this->workflow->can($courseAttendee, $transition)) {
            throw new CourseAttendeeTransitionNotAvailableException($courseAttendee, $transition);
        }

        $this->workflow->apply($courseAttendee, $transition);
        $this->eventCollector->push($event::COMPONENT_NAME, $event::NAME, $event);
    }
}
