<?php

namespace Sprungbrett\Bundle\CourseBundle\Model\Attendee\Handler;

use Sprungbrett\Bundle\CourseBundle\Model\Attendee\AttendeeRepositoryInterface;
use Sprungbrett\Bundle\CourseBundle\Model\Attendee\Query\CountBookmarksQuery;

class CountBookmarkQueryHandler
{
    /**
     * @var AttendeeRepositoryInterface
     */
    private $attendeeRepository;

    public function __construct(AttendeeRepositoryInterface $attendeeRepository)
    {
        $this->attendeeRepository = $attendeeRepository;
    }

    public function handle(CountBookmarksQuery $query): int
    {
        return $this->attendeeRepository->countBookmarks($query->getCourseId());
    }
}
