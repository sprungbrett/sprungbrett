<?php

namespace Sprungbrett\Component\Course\Tests\Unit\Model\Handler;

use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Sprungbrett\Component\Course\Model\Command\ModifyCourseCommand;
use Sprungbrett\Component\Course\Model\Course;
use Sprungbrett\Component\Course\Model\CourseRepositoryInterface;
use Sprungbrett\Component\Course\Model\Event\CourseModifiedEvent;
use Sprungbrett\Component\Course\Model\Handler\ModifyCourseHandler;
use Sprungbrett\Component\EventCollector\EventCollector;
use Sprungbrett\Component\Translation\Model\Localization;

class ModifyCourseHandlerTest extends TestCase
{
    public function testHandle()
    {
        $repository = $this->prophesize(CourseRepositoryInterface::class);
        $eventCollector = $this->prophesize(EventCollector::class);
        $handler = new ModifyCourseHandler($repository->reveal(), $eventCollector->reveal());

        $localization = $this->prophesize(Localization::class);

        $course = $this->prophesize(Course::class);
        $repository->findById('123-123-123', $localization->reveal())->willReturn($course->reveal());
        $course->setTitle('Sprungbrett')->shouldBeCalled();
        $course->setDescription('Sprungbrett is awesome')->shouldBeCalled();
        $course->setStructureType('default')->shouldBeCalled();
        $course->setContentData(['title' => 'Sprungbrett is awesome'])->shouldBeCalled();

        $command = $this->prophesize(ModifyCourseCommand::class);
        $command->getId()->willReturn('123-123-123');
        $command->getLocalization()->willReturn($localization->reveal());
        $command->getTitle()->willReturn('Sprungbrett');
        $command->getDescription()->willReturn('Sprungbrett is awesome');
        $command->getStructureType()->willReturn('default');
        $command->getContentData()->willReturn(['title' => 'Sprungbrett is awesome']);

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
