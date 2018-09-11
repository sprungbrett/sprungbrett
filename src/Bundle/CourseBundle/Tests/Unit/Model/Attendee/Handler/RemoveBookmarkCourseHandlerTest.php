<?php

namespace Sprungbrett\Bundle\CourseBundle\Tests\Unit\Model\Attendee\Handler;

use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Sprungbrett\Bundle\CourseBundle\Model\Attendee\AttendeeInterface;
use Sprungbrett\Bundle\CourseBundle\Model\Attendee\AttendeeRepositoryInterface;
use Sprungbrett\Bundle\CourseBundle\Model\Attendee\Command\RemoveBookmarkCourseCommand;
use Sprungbrett\Bundle\CourseBundle\Model\Attendee\Event\AttendeeRemovedBookmarkedCourseEvent;
use Sprungbrett\Bundle\CourseBundle\Model\Attendee\Handler\RemoveBookmarkCourseHandler;
use Sprungbrett\Bundle\CourseBundle\Model\Course\CourseInterface;
use Sprungbrett\Bundle\CourseBundle\Model\Course\CourseRepositoryInterface;
use Sprungbrett\Component\EventCollector\EventCollector;
use Sprungbrett\Component\Translation\Model\Localization;

class RemoveBookmarkCourseHandlerTest extends TestCase
{
    public function testHandle()
    {
        $attendeeRepository = $this->prophesize(AttendeeRepositoryInterface::class);
        $courseRepository = $this->prophesize(CourseRepositoryInterface::class);
        $eventCollector = $this->prophesize(EventCollector::class);

        $handler = new RemoveBookmarkCourseHandler(
            $attendeeRepository->reveal(),
            $courseRepository->reveal(),
            $eventCollector->reveal()
        );

        $localization = $this->prophesize(Localization::class);

        $command = $this->prophesize(RemoveBookmarkCourseCommand::class);
        $command->getCourseId()->willReturn('123-123-123');
        $command->getAttendeeId()->willReturn(42);
        $command->getLocalization()->willReturn($localization->reveal());

        $attendee = $this->prophesize(AttendeeInterface::class);
        $course = $this->prophesize(CourseInterface::class);

        $attendee->removeBookmark($course->reveal())->willReturn($attendee->reveal())->shouldBeCalled();

        $attendeeRepository->findOrCreateAttendeeById(42)->willReturn($attendee->reveal());
        $courseRepository->findById('123-123-123', $localization->reveal())->willReturn($course->reveal());

        $eventCollector->push(
            AttendeeRemovedBookmarkedCourseEvent::COMPONENT_NAME,
            AttendeeRemovedBookmarkedCourseEvent::NAME,
            Argument::that(
                function (AttendeeRemovedBookmarkedCourseEvent $event) use ($course, $attendee) {
                    return $event->getAttendee() === $attendee->reveal() && $event->getCourse() === $course->reveal();
                }
            )
        )->shouldBeCalled();

        $this->assertEquals($course->reveal(), $handler->handle($command->reveal()));
    }
}
