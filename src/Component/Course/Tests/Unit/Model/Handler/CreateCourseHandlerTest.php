<?php

namespace Sprungbrett\Component\Course\Tests\Unit\Model\Handler;

use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Sprungbrett\Component\Course\Model\Command\CreateCourseCommand;
use Sprungbrett\Component\Course\Model\Course;
use Sprungbrett\Component\Course\Model\CourseRepositoryInterface;
use Sprungbrett\Component\Course\Model\Event\CourseCreatedEvent;
use Sprungbrett\Component\Course\Model\Handler\CreateCourseHandler;
use Sprungbrett\Component\EventCollector\EventCollector;
use Sprungbrett\Component\Translation\Model\Localization;

class CreateCourseHandlerTest extends TestCase
{
    public function testHandle()
    {
        $repository = $this->prophesize(CourseRepositoryInterface::class);
        $eventCollector = $this->prophesize(EventCollector::class);
        $handler = new CreateCourseHandler($repository->reveal(), $eventCollector->reveal());

        $localization = $this->prophesize(Localization::class);

        $course = $this->prophesize(Course::class);
        $repository->create($localization->reveal())->willReturn($course->reveal());
        $course->setTitle('Sprungbrett')->shouldBeCalled();
        $course->setDescription('Sprungbrett is awesome')->shouldBeCalled();

        $command = $this->prophesize(CreateCourseCommand::class);
        $command->getLocalization()->willReturn($localization->reveal());
        $command->getTitle()->willReturn('Sprungbrett');
        $command->getDescription()->willReturn('Sprungbrett is awesome');

        $eventCollector->push(
            'course',
            'created',
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
