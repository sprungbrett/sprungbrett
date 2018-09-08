<?php

namespace Sprungbrett\Bundle\CourseBundle\Tests\Unit\Model\Attendee\Handler;

use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Sprungbrett\Bundle\CourseBundle\Model\Attendee\AttendeeInterface;
use Sprungbrett\Bundle\CourseBundle\Model\Attendee\Command\ShowInterestCommand;
use Sprungbrett\Bundle\CourseBundle\Model\Attendee\CourseAttendeeInterface;
use Sprungbrett\Bundle\CourseBundle\Model\Attendee\CourseAttendeeRepositoryInterface;
use Sprungbrett\Bundle\CourseBundle\Model\Attendee\Event\AttendeeShowedInterestEvent;
use Sprungbrett\Bundle\CourseBundle\Model\Attendee\Handler\ShowInterestHandler;
use Sprungbrett\Bundle\CourseBundle\Model\Course\CourseInterface;
use Sprungbrett\Component\EventCollector\EventCollector;
use Sprungbrett\Component\Translation\Model\Localization;
use Symfony\Component\Workflow\Marking;
use Symfony\Component\Workflow\Workflow;

class ShowInterestHandlerTest extends TestCase
{
    public function testHandle()
    {
        $courseAttendeeRepository = $this->prophesize(CourseAttendeeRepositoryInterface::class);
        $workflow = $this->prophesize(Workflow::class);
        $eventCollector = $this->prophesize(EventCollector::class);

        $handler = new ShowInterestHandler(
            $courseAttendeeRepository->reveal(),
            $workflow->reveal(),
            $eventCollector->reveal()
        );

        $localization = $this->prophesize(Localization::class);

        $command = $this->prophesize(ShowInterestCommand::class);
        $command->getCourseId()->willReturn('123-123-123');
        $command->getAttendeeId()->willReturn(42);
        $command->getLocalization()->willReturn($localization->reveal());

        $courseAttendee = $this->prophesize(CourseAttendeeInterface::class);
        $courseAttendee->getAttendee()->willReturn($this->prophesize(AttendeeInterface::class)->reveal());
        $courseAttendee->getCourse()->willReturn($this->prophesize(CourseInterface::class)->reveal());
        $courseAttendeeRepository->findOrCreateCourseAttendeeById(42, '123-123-123', $localization->reveal())
            ->willReturn($courseAttendee->reveal());

        $workflow->can($courseAttendee->reveal(), CourseAttendeeInterface::TRANSITION_SHOW_INTEREST)->willReturn(true);
        $workflow->apply($courseAttendee->reveal(), CourseAttendeeInterface::TRANSITION_SHOW_INTEREST)
            ->willReturn(new Marking())
            ->shouldBeCalled();

        $eventCollector->push(
            AttendeeShowedInterestEvent::COMPONENT_NAME,
            AttendeeShowedInterestEvent::NAME,
            Argument::that(
                function (AttendeeShowedInterestEvent $event) use ($courseAttendee) {
                    return $event->getCourseAttendee() === $courseAttendee->reveal();
                }
            )
        )->shouldBeCalled();

        $this->assertEquals($courseAttendee->reveal(), $handler->handle($command->reveal()));
    }
}
