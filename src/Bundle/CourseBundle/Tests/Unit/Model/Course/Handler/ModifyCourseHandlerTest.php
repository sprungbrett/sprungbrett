<?php

namespace Sprungbrett\Bundle\CourseBundle\Tests\Unit\Model\Course\Handler;

use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Sprungbrett\Bundle\CourseBundle\Model\Course\Command\ModifyCourseCommand;
use Sprungbrett\Bundle\CourseBundle\Model\Course\CourseInterface;
use Sprungbrett\Bundle\CourseBundle\Model\Course\CourseRepositoryInterface;
use Sprungbrett\Bundle\CourseBundle\Model\Course\Event\CourseModifiedEvent;
use Sprungbrett\Bundle\CourseBundle\Model\Course\Handler\ModifyCourseHandler;
use Sprungbrett\Bundle\CourseBundle\Model\Trainer\TrainerInterface;
use Sprungbrett\Bundle\CourseBundle\Model\Trainer\TrainerRepositoryInterface;
use Sprungbrett\Component\EventCollector\EventCollector;
use Sprungbrett\Component\Translation\Model\Localization;

class ModifyCourseHandlerTest extends TestCase
{
    public function testHandle()
    {
        $repository = $this->prophesize(CourseRepositoryInterface::class);
        $trainerRepository = $this->prophesize(TrainerRepositoryInterface::class);
        $eventCollector = $this->prophesize(EventCollector::class);
        $handler = new ModifyCourseHandler(
            $repository->reveal(),
            $trainerRepository->reveal(),
            $eventCollector->reveal()
        );

        $localization = $this->prophesize(Localization::class);

        $course = $this->prophesize(CourseInterface::class);
        $course->getLocalization()->willReturn($localization->reveal());
        $repository->findById('123-123-123', $localization->reveal())->willReturn($course->reveal());
        $course->setName('Sprungbrett')->shouldBeCalled();
        $course->setDescription('Sprungbrett is awesome')->shouldBeCalled();
        $course->setStructureType('default')->shouldBeCalled();
        $course->setContentData(['title' => 'Sprungbrett is awesome'])->shouldBeCalled();
        $course->setTrainerId(42)->shouldBeCalled();

        $command = $this->prophesize(ModifyCourseCommand::class);
        $command->getId()->willReturn('123-123-123');
        $command->getLocalization()->willReturn($localization->reveal());
        $command->getName()->willReturn('Sprungbrett');
        $command->getDescription()->willReturn('Sprungbrett is awesome');
        $command->getStructureType()->willReturn('default');
        $command->getContentData()->willReturn(['title' => 'Sprungbrett is awesome']);
        $command->getTrainer()->willReturn(['id' => 42]);

        $trainer = $this->prophesize(TrainerInterface::class);
        $trainer->getId()->willReturn(42);
        $trainerRepository->findOrCreateTrainerById(42, $localization->reveal())->willReturn($trainer->reveal());

        $eventCollector->push(
            CourseModifiedEvent::COMPONENT_NAME,
            CourseModifiedEvent::NAME,
            Argument::that(
                function (CourseModifiedEvent $event) use ($course) {
                    return $course->reveal() === $event->getCourse();
                }
            )
        )->shouldBeCalled();

        $result = $handler->handle($command->reveal());
        $this->assertEquals($course->reveal(), $result);
    }
}
