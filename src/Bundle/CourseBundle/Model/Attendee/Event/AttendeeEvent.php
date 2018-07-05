<?php

namespace Sprungbrett\Bundle\CourseBundle\Model\Attendee\Event;

use Sprungbrett\Bundle\CourseBundle\Model\Attendee\AttendeeInterface;
use Symfony\Component\EventDispatcher\Event;

abstract class AttendeeEvent extends Event
{
    const COMPONENT_NAME = 'attendee';
    const NAME = self::NAME;

    /**
     * @var AttendeeInterface
     */
    private $attendee;

    public function __construct(AttendeeInterface $attendee)
    {
        $this->attendee = $attendee;
    }

    public function getAttendee(): AttendeeInterface
    {
        return $this->attendee;
    }
}
