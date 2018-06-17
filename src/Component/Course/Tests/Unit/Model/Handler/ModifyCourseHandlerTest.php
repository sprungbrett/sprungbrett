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
use Sprungbrett\Component\Uuid\Model\Uuid;

class ModifyCourseHandlerTest extends TestCase
{
    public function testHandle()
    {
        $repository = $this->prophesize(CourseRepositoryInterface::class);
        $eventCollector = $this->prophesize(EventCollector::class);
        $handler = new ModifyCourseHandler($repository->reveal(), $eventCollector->reveal());

        $uuid = $this->prophesize(Uuid::class);
        $localization = $this->prophesize(Localization::class);

        $course = $this->prophesize(Course::class);
        $repository->findByUuid($uuid->reveal(), $localization->reveal())->willReturn($course->reveal());
        $course->setTitle('Sprungbrett')->shouldBeCalled();
        $course->setDescription('Sprungbrett is awesome')->shouldBeCalled();

        $command = $this->prophesize(ModifyCourseCommand::class);
        $command->getUuid()->willReturn($uuid->reveal());
        $command->getLocalization()->willReturn($localization->reveal());
        $command->getTitle()->willReturn('Sprungbrett');
        $command->getDescription()->willReturn('Sprungbrett is awesome');

        $eventCollector->push(
            'course',
            'modified',
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
