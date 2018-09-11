<?php

namespace Sprungbrett\Bundle\CourseBundle\Model\Attendee\Handler;

use Sprungbrett\Bundle\CourseBundle\Model\Attendee\AttendeeRepositoryInterface;
use Sprungbrett\Bundle\CourseBundle\Model\Attendee\Query\HasBookmarkQuery;

class HasBookmarkQueryHandler
{
    /**
     * @var AttendeeRepositoryInterface
     */
    private $attendeeRepository;

    public function __construct(AttendeeRepositoryInterface $attendeeRepository)
    {
        $this->attendeeRepository = $attendeeRepository;
    }

    public function handle(HasBookmarkQuery $query): bool
    {
        return $this->attendeeRepository->hasBookmark($query->getAttendeeId(), $query->getCourseId());
    }
}
