<?php

namespace Sprungbrett\Bundle\CourseBundle\Model\Attendee\Handler;

use Sprungbrett\Bundle\CourseBundle\Model\Attendee\AttendeeRepositoryInterface;
use Sprungbrett\Bundle\CourseBundle\Model\Attendee\Command\BookmarkCourseCommand;
use Sprungbrett\Bundle\CourseBundle\Model\Attendee\Event\AttendeeBookmarkedCourseEvent;
use Sprungbrett\Bundle\CourseBundle\Model\Course\CourseInterface;
use Sprungbrett\Bundle\CourseBundle\Model\Course\CourseRepositoryInterface;
use Sprungbrett\Component\EventCollector\EventCollector;

class BookmarkCourseHandler
{
    /**
     * @var AttendeeRepositoryInterface
     */
    private $attendeeRepository;

    /**
     * @var CourseRepositoryInterface
     */
    private $courseRepository;

    /**
     * @var EventCollector
     */
    private $eventCollector;

    public function __construct(
        AttendeeRepositoryInterface $attendeeRepository,
        CourseRepositoryInterface $courseRepository,
        EventCollector $eventCollector
    ) {
        $this->attendeeRepository = $attendeeRepository;
        $this->courseRepository = $courseRepository;
        $this->eventCollector = $eventCollector;
    }

    public function handle(BookmarkCourseCommand $command): CourseInterface
    {
        $course = $this->courseRepository->findById($command->getCourseId(), $command->getLocalization());
        $attendee = $this->attendeeRepository->findOrCreateAttendeeById($command->getAttendeeId());
        $attendee->bookmark($course);

        $this->eventCollector->push(
            AttendeeBookmarkedCourseEvent::COMPONENT_NAME,
            AttendeeBookmarkedCourseEvent::NAME,
            new AttendeeBookmarkedCourseEvent($attendee, $course)
        );

        return $course;
    }
}
