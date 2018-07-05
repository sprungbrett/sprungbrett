<?php

namespace Sprungbrett\Bundle\CourseBundle\Tests\Unit\Model\Attendee\Handler;

use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Sprungbrett\Bundle\CourseBundle\Model\Attendee\AttendeeInterface;
use Sprungbrett\Bundle\CourseBundle\Model\Attendee\AttendeeRepositoryInterface;
use Sprungbrett\Bundle\CourseBundle\Model\Attendee\Command\ModifyAttendeeCommand;
use Sprungbrett\Bundle\CourseBundle\Model\Attendee\Event\AttendeeModifiedEvent;
use Sprungbrett\Bundle\CourseBundle\Model\Attendee\Handler\ModifyAttendeeHandler;
use Sprungbrett\Component\EventCollector\EventCollector;
use Sprungbrett\Component\Translation\Model\Localization;

class ModifyAttendeeHandlerTest extends TestCase
{
    public function testHandle()
    {
        $repository = $this->prophesize(AttendeeRepositoryInterface::class);
        $eventCollector = $this->prophesize(EventCollector::class);
        $handler = new ModifyAttendeeHandler($repository->reveal(), $eventCollector->reveal());

        $localization = $this->prophesize(Localization::class);

        $attendee = $this->prophesize(AttendeeInterface::class);
        $repository->findOrCreateAttendeeById(42, $localization->reveal())->willReturn($attendee->reveal());
        $attendee->setDescription('Sprungbrett is awesome')->shouldBeCalled();

        $command = $this->prophesize(ModifyAttendeeCommand::class);
        $command->getId()->willReturn(42);
        $command->getLocalization()->willReturn($localization->reveal());
        $command->getDescription()->willReturn('Sprungbrett is awesome');

        $eventCollector->push(
            AttendeeModifiedEvent::COMPONENT_NAME,
            AttendeeModifiedEvent::NAME,
            Argument::that(
                function (AttendeeModifiedEvent $event) use ($attendee) {
                    return $attendee->reveal() === $event->getAttendee();
                }
            )
        )->shouldBeCalled();

        $result = $handler->handle($command->reveal());
        $this->assertEquals($attendee->reveal(), $result);
    }
}
