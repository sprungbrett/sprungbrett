<?php

namespace Sprungbrett\Bundle\CourseBundle\Model\Attendee\Handler;

use Sprungbrett\Bundle\CourseBundle\Model\Attendee\CourseAttendeeRepositoryInterface;
use Sprungbrett\Bundle\CourseBundle\Model\Attendee\Query\CountAttendeeQuery;

class CountAttendeeQueryHandler
{
    /**
     * @var CourseAttendeeRepositoryInterface
     */
    private $courseAttendeeRepository;

    public function __construct(CourseAttendeeRepositoryInterface $courseAttendeeRepository)
    {
        $this->courseAttendeeRepository = $courseAttendeeRepository;
    }

    public function handle(CountAttendeeQuery $query): int
    {
        return $this->courseAttendeeRepository->countByCourse($query->getCourseId());
    }
}
