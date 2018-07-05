<?php

namespace Sprungbrett\Bundle\CourseBundle\Model\Attendee\Handler;

use Sprungbrett\Bundle\CourseBundle\Model\Attendee\AttendeeInterface;
use Sprungbrett\Bundle\CourseBundle\Model\Attendee\AttendeeRepositoryInterface;
use Sprungbrett\Bundle\CourseBundle\Model\Attendee\Command\ModifyAttendeeCommand;
use Sprungbrett\Bundle\CourseBundle\Model\Attendee\Event\AttendeeModifiedEvent;
use Sprungbrett\Component\EventCollector\EventCollector;

class ModifyAttendeeHandler
{
    /**
     * @var AttendeeRepositoryInterface
     */
    private $attendeeRepository;

    /**
     * @var EventCollector
     */
    private $eventCollector;

    public function __construct(AttendeeRepositoryInterface $attendeeRepository, EventCollector $eventCollector)
    {
        $this->attendeeRepository = $attendeeRepository;
        $this->eventCollector = $eventCollector;
    }

    public function handle(ModifyAttendeeCommand $command): AttendeeInterface
    {
        $attendee = $this->attendeeRepository->findOrCreateAttendeeById($command->getId(), $command->getLocalization());
        $attendee->setDescription($command->getDescription());

        $event = new AttendeeModifiedEvent($attendee);
        $this->eventCollector->push($event::COMPONENT_NAME, $event::NAME, $event);

        return $attendee;
    }
}
