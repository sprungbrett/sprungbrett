<?php

namespace Sprungbrett\Bundle\CourseBundle\Tests\Unit\Model\Course\Handler;

use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Sprungbrett\Bundle\CourseBundle\Model\Course\Command\CreateCourseCommand;
use Sprungbrett\Bundle\CourseBundle\Model\Course\CourseInterface;
use Sprungbrett\Bundle\CourseBundle\Model\Course\CourseRepositoryInterface;
use Sprungbrett\Bundle\CourseBundle\Model\Course\Event\CourseCreatedEvent;
use Sprungbrett\Bundle\CourseBundle\Model\Course\Handler\CreateCourseHandler;
use Sprungbrett\Bundle\CourseBundle\Model\Trainer\TrainerInterface;
use Sprungbrett\Bundle\CourseBundle\Model\Trainer\TrainerRepositoryInterface;
use Sprungbrett\Component\EventCollector\EventCollector;
use Sprungbrett\Component\Translation\Model\Localization;
use Symfony\Component\Workflow\Marking;
use Symfony\Component\Workflow\Workflow;

class CreateCourseHandlerTest extends TestCase
{
    public function testHandle()
    {
        $repository = $this->prophesize(CourseRepositoryInterface::class);
        $trainerRepository = $this->prophesize(TrainerRepositoryInterface::class);
        $workflow = $this->prophesize(Workflow::class);
        $eventCollector = $this->prophesize(EventCollector::class);
        $handler = new CreateCourseHandler(
            $repository->reveal(),
            $trainerRepository->reveal(),
            $workflow->reveal(),
            $eventCollector->reveal(),
            'default'
        );

        $localization = $this->prophesize(Localization::class);

        $course = $this->prophesize(CourseInterface::class);
        $course->getLocalization()->willReturn($localization->reveal());
        $repository->create($localization->reveal())->willReturn($course->reveal());
        $course->setName('Sprungbrett')->shouldBeCalled();
        $course->setDescription('Sprungbrett is awesome')->shouldBeCalled();
        $course->setStructureType('default')->shouldBeCalled();
        $course->setTrainerId(42)->shouldBeCalled();

        $command = $this->prophesize(CreateCourseCommand::class);
        $command->getLocalization()->willReturn($localization->reveal());
        $command->getName()->willReturn('Sprungbrett');
        $command->getDescription()->willReturn('Sprungbrett is awesome');
        $command->getTrainer()->willReturn(['id' => 42]);

        $trainer = $this->prophesize(TrainerInterface::class);
        $trainer->getId()->willReturn(42);
        $trainerRepository->findOrCreateTrainerById(42, $localization->reveal())->willReturn($trainer->reveal());

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
