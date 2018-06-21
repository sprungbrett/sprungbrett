<?php

namespace Sprungbrett\Component\Course\Model\Handler;

use Sprungbrett\Component\Course\Model\CourseInterface;
use Sprungbrett\Component\Course\Model\Event\CourseEvent;
use Sprungbrett\Component\Course\Model\Exception\CourseTransitionNotAvailableException;
use Sprungbrett\Component\EventCollector\EventCollector;
use Symfony\Component\Workflow\Workflow;

trait CourseTransitTrait
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

    protected function transition(string $transition, CourseEvent $event, CourseInterface $course): void
    {
        if (!$this->workflow->can($course, $transition)) {
            throw new CourseTransitionNotAvailableException($course, $transition);
        }

        $this->workflow->apply($course, $transition);
        $this->eventCollector->push($event::COMPONENT_NAME, $event::NAME, $event);
    }
}
