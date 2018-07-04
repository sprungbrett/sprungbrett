<?php

namespace Sprungbrett\Bundle\CourseBundle\Tests\Unit\Model\Course\Handler;

use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Sprungbrett\Bundle\CourseBundle\Model\Course\Command\CreateCourseCommand;
use Sprungbrett\Bundle\CourseBundle\Model\Course\CourseInterface;
use Sprungbrett\Bundle\CourseBundle\Model\Course\CourseRepositoryInterface;
use Sprungbrett\Bundle\CourseBundle\Model\Course\Event\CourseCreatedEvent;
use Sprungbrett\Bundle\CourseBundle\Model\Course\Handler\CreateCourseHandler;
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
        $handler = new CreateCourseHandler(
            $repository->reveal(),
            $workflow->reveal(),
            $eventCollector->reveal(),
            'default'
        );

        $localization = $this->prophesize(Localization::class);

        $course = $this->prophesize(CourseInterface::class);
        $repository->create($localization->reveal())->willReturn($course->reveal());
        $course->setTitle('Sprungbrett')->shouldBeCalled();
        $course->setDescription('Sprungbrett is awesome')->shouldBeCalled();
        $course->setStructureType('default')->shouldBeCalled();
        $course->setTrainerId(42)->shouldBeCalled();

        $command = $this->prophesize(CreateCourseCommand::class);
        $command->getLocalization()->willReturn($localization->reveal());
        $command->getTitle()->willReturn('Sprungbrett');
        $command->getDescription()->willReturn('Sprungbrett is awesome');
        $command->getTrainer()->willReturn(['id' => 42]);

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
