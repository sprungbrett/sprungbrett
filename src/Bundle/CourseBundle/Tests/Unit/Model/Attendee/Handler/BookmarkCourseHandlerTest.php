<?php

namespace Sprungbrett\Bundle\CourseBundle\Tests\Unit\Model\Attendee\Handler;

use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Sprungbrett\Bundle\CourseBundle\Model\Attendee\AttendeeInterface;
use Sprungbrett\Bundle\CourseBundle\Model\Attendee\AttendeeRepositoryInterface;
use Sprungbrett\Bundle\CourseBundle\Model\Attendee\Command\BookmarkCourseCommand;
use Sprungbrett\Bundle\CourseBundle\Model\Attendee\Event\AttendeeBookmarkedCourseEvent;
use Sprungbrett\Bundle\CourseBundle\Model\Attendee\Handler\BookmarkCourseHandler;
use Sprungbrett\Bundle\CourseBundle\Model\Course\CourseInterface;
use Sprungbrett\Bundle\CourseBundle\Model\Course\CourseRepositoryInterface;
use Sprungbrett\Component\EventCollector\EventCollector;
use Sprungbrett\Component\Translation\Model\Localization;

class BookmarkCourseHandlerTest extends TestCase
{
    public function testHandle()
    {
        $attendeeRepository = $this->prophesize(AttendeeRepositoryInterface::class);
        $courseRepository = $this->prophesize(CourseRepositoryInterface::class);
        $eventCollector = $this->prophesize(EventCollector::class);

        $handler = new BookmarkCourseHandler(
            $attendeeRepository->reveal(),
            $courseRepository->reveal(),
            $eventCollector->reveal()
        );

        $localization = $this->prophesize(Localization::class);

        $command = $this->prophesize(BookmarkCourseCommand::class);
        $command->getCourseId()->willReturn('123-123-123');
        $command->getAttendeeId()->willReturn(42);
        $command->getLocalization()->willReturn($localization->reveal());

        $attendee = $this->prophesize(AttendeeInterface::class);
        $course = $this->prophesize(CourseInterface::class);

        $attendee->bookmark($course->reveal())->willReturn($attendee->reveal())->shouldBeCalled();

        $attendeeRepository->findOrCreateAttendeeById(42)->willReturn($attendee->reveal());
        $courseRepository->findById('123-123-123', $localization->reveal())->willReturn($course->reveal());

        $eventCollector->push(
            AttendeeBookmarkedCourseEvent::COMPONENT_NAME,
            AttendeeBookmarkedCourseEvent::NAME,
            Argument::that(
                function (AttendeeBookmarkedCourseEvent $event) use ($course, $attendee) {
                    return $event->getAttendee() === $attendee->reveal() && $event->getCourse() === $course->reveal();
                }
            )
        )->shouldBeCalled();

        $this->assertEquals($course->reveal(), $handler->handle($command->reveal()));
    }
}
