<?php

namespace Sprungbrett\Bundle\CourseBundle\Model\Attendee\Handler;

use Sprungbrett\Bundle\CourseBundle\Model\Attendee\Command\ShowInterestCommand;
use Sprungbrett\Bundle\CourseBundle\Model\Attendee\CourseAttendeeInterface;
use Sprungbrett\Bundle\CourseBundle\Model\Attendee\CourseAttendeeRepositoryInterface;
use Sprungbrett\Bundle\CourseBundle\Model\Attendee\Event\AttendeeShowedInterestEvent;
use Sprungbrett\Component\EventCollector\EventCollector;
use Symfony\Component\Workflow\Workflow;

class ShowInterestHandler
{
    use CourseAttendeeTransitTrait;

    /**
     * @var CourseAttendeeRepositoryInterface
     */
    private $courseAttendeeRepository;

    public function __construct(
        CourseAttendeeRepositoryInterface $courseAttendeeRepository,
        Workflow $workflow,
        EventCollector $eventCollector
    ) {
        $this->initializeTransit($workflow, $eventCollector);

        $this->courseAttendeeRepository = $courseAttendeeRepository;
        $this->workflow = $workflow;
        $this->eventCollector = $eventCollector;
    }

    public function handle(ShowInterestCommand $command): CourseAttendeeInterface
    {
        $courseAttendee = $this->courseAttendeeRepository->findOrCreateCourseAttendeeById(
            $command->getAttendeeId(),
            $command->getCourseId()
        );

        $this->transition(
            CourseAttendeeInterface::TRANSITION_SHOW_INTEREST,
            new AttendeeShowedInterestEvent($courseAttendee),
            $courseAttendee
        );

        return $courseAttendee;
    }
}
