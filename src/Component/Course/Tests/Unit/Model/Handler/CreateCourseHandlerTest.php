<?php

namespace Sprungbrett\Component\Course\Tests\Unit\Model\Handler;

use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Sprungbrett\Component\Course\Model\Command\CreateCourseCommand;
use Sprungbrett\Component\Course\Model\Course;
use Sprungbrett\Component\Course\Model\CourseInterface;
use Sprungbrett\Component\Course\Model\CourseRepositoryInterface;
use Sprungbrett\Component\Course\Model\Event\CourseCreatedEvent;
use Sprungbrett\Component\Course\Model\Handler\CreateCourseHandler;
use Sprungbrett\Component\EventCollector\EventCollector;
use Sprungbrett\Component\Translation\Model\Localization;
use Symfony\Component\Workflow\Marking;
use Symfony\Component\Workflow\Workflow;

class CreateCourseHandlerTest extends TestCase
{
    public function testHandle()
    {
        $repository = $this->prophesize(CourseRepositoryInterface::class);
        $workflow = $this->prophesize(Workflow::class);
        $eventCollector = $this->prophesize(EventCollector::class);
        $handler = new CreateCourseHandler($repository->reveal(), $workflow->reveal(), $eventCollector->reveal());

        $localization = $this->prophesize(Localization::class);

        $course = $this->prophesize(Course::class);
        $repository->create($localization->reveal())->willReturn($course->reveal());
        $course->setTitle('Sprungbrett')->shouldBeCalled();
        $course->setDescription('Sprungbrett is awesome')->shouldBeCalled();

        $command = $this->prophesize(CreateCourseCommand::class);
        $command->getLocalization()->willReturn($localization->reveal());
        $command->getTitle()->willReturn('Sprungbrett');
        $command->getDescription()->willReturn('Sprungbrett is awesome');

        $marking = $this->prophesize(Marking::class);
        $workflow->can($course->reveal(), CourseInterface::TRANSITION_CREATE)->willReturn(true);
        $workflow->apply($course->reveal(), CourseInterface::TRANSITION_CREATE)
            ->shouldBeCalled()
            ->willReturn($marking->reveal());

        $eventCollector->push(
            CourseCreatedEvent::COMPONENT_NAME,
            CourseCreatedEvent::NAME,
            Argument::that(
                function (CourseCreatedEvent $event) use ($course) {
                    return $course->reveal() === $event->getCourse();
                }
            )
        )->shouldBeCalled();

        $result = $handler->handle($command->reveal());
        $this->assertEquals($course->reveal(), $result);
    }
}
