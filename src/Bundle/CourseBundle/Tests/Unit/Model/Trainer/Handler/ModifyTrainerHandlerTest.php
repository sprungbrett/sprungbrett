<?php

namespace Sprungbrett\Bundle\CourseBundle\Tests\Unit\Model\Trainer\Handler;

use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Sprungbrett\Bundle\CourseBundle\Model\Trainer\Command\ModifyTrainerCommand;
use Sprungbrett\Bundle\CourseBundle\Model\Trainer\Event\TrainerModifiedEvent;
use Sprungbrett\Bundle\CourseBundle\Model\Trainer\Handler\ModifyTrainerHandler;
use Sprungbrett\Bundle\CourseBundle\Model\Trainer\TrainerInterface;
use Sprungbrett\Bundle\CourseBundle\Model\Trainer\TrainerRepositoryInterface;
use Sprungbrett\Component\EventCollector\EventCollector;
use Sprungbrett\Component\Translation\Model\Localization;

class ModifyTrainerHandlerTest extends TestCase
{
    public function testHandle()
    {
        $repository = $this->prophesize(TrainerRepositoryInterface::class);
        $eventCollector = $this->prophesize(EventCollector::class);
        $handler = new ModifyTrainerHandler($repository->reveal(), $eventCollector->reveal());

        $localization = $this->prophesize(Localization::class);

        $trainer = $this->prophesize(TrainerInterface::class);
        $repository->findOrCreateTrainerById(42, $localization->reveal())->willReturn($trainer->reveal());
        $trainer->setDescription('Sprungbrett is awesome')->shouldBeCalled();

        $command = $this->prophesize(ModifyTrainerCommand::class);
        $command->getId()->willReturn(42);
        $command->getLocalization()->willReturn($localization->reveal());
        $command->getDescription()->willReturn('Sprungbrett is awesome');

        $eventCollector->push(
            TrainerModifiedEvent::COMPONENT_NAME,
            TrainerModifiedEvent::NAME,
            Argument::that(
                function (TrainerModifiedEvent $event) use ($trainer) {
                    return $trainer->reveal() === $event->getTrainer();
                }
            )
        )->shouldBeCalled();

        $result = $handler->handle($command->reveal());
        $this->assertEquals($trainer->reveal(), $result);
    }
}
