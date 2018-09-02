<?php

namespace Sprungbrett\Bundle\CourseBundle\Model\Attendee\Handler;

use Sprungbrett\Bundle\CourseBundle\Model\Attendee\CourseAttendeeRepositoryInterface;
use Sprungbrett\Bundle\CourseBundle\Model\Attendee\Query\IsAttendeeQuery;

class IsAttendeeQueryHandler
{
    /**
     * @var CourseAttendeeRepositoryInterface
     */
    private $courseAttendeeRepository;

    public function __construct(CourseAttendeeRepositoryInterface $courseAttendeeRepository)
    {
        $this->courseAttendeeRepository = $courseAttendeeRepository;
    }

    public function handle(IsAttendeeQuery $query): bool
    {
        return $this->courseAttendeeRepository->exists($query->getAttendeeId(), $query->getCourseId());
    }
}
