<?php

namespace Sprungbrett\Bundle\CourseBundle\Model\Attendee\Handler;

use Sprungbrett\Bundle\CourseBundle\Model\Attendee\AttendeeRepositoryInterface;
use Sprungbrett\Bundle\CourseBundle\Model\Attendee\Command\RemoveBookmarkCourseCommand;
use Sprungbrett\Bundle\CourseBundle\Model\Attendee\Event\AttendeeRemovedBookmarkedCourseEvent;
use Sprungbrett\Bundle\CourseBundle\Model\Course\CourseInterface;
use Sprungbrett\Bundle\CourseBundle\Model\Course\CourseRepositoryInterface;
use Sprungbrett\Component\EventCollector\EventCollector;

class RemoveBookmarkCourseHandler
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

    public function handle(RemoveBookmarkCourseCommand $command): CourseInterface
    {
        $course = $this->courseRepository->findById($command->getCourseId(), $command->getLocalization());
        $attendee = $this->attendeeRepository->findOrCreateAttendeeById($command->getAttendeeId());
        $attendee->removeBookmark($course);

        $this->eventCollector->push(
            AttendeeRemovedBookmarkedCourseEvent::COMPONENT_NAME,
            AttendeeRemovedBookmarkedCourseEvent::NAME,
            new AttendeeRemovedBookmarkedCourseEvent($attendee, $course)
        );

        return $course;
    }
}
