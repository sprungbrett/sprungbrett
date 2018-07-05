<?php

namespace Sprungbrett\Bundle\CourseBundle\Model\Attendee\Handler;

use Sprungbrett\Bundle\CourseBundle\Model\Attendee\AttendeeInterface;
use Sprungbrett\Bundle\CourseBundle\Model\Attendee\AttendeeRepositoryInterface;
use Sprungbrett\Bundle\CourseBundle\Model\Attendee\Query\FindAttendeeQuery;

class FindAttendeeQueryHandler
{
    /**
     * @var AttendeeRepositoryInterface
     */
    private $attendeeRepository;

    public function __construct(AttendeeRepositoryInterface $attendeeRepository)
    {
        $this->attendeeRepository = $attendeeRepository;
    }

    public function handle(FindAttendeeQuery $query): AttendeeInterface
    {
        return $this->attendeeRepository->findOrCreateAttendeeById($query->getId(), $query->getLocalization());
    }
}
