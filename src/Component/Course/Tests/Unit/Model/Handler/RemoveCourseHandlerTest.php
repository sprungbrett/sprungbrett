<?php

namespace Sprungbrett\Component\Course\Tests\Unit\Model\Handler;

use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Sprungbrett\Component\Course\Model\Command\RemoveCourseCommand;
use Sprungbrett\Component\Course\Model\Course;
use Sprungbrett\Component\Course\Model\CourseRepositoryInterface;
use Sprungbrett\Component\Course\Model\Event\CourseRemovedEvent;
use Sprungbrett\Component\Course\Model\Handler\RemoveCourseHandler;
use Sprungbrett\Component\EventCollector\EventCollector;
use Sprungbrett\Component\Uuid\Model\Uuid;

class RemoveCourseHandlerTest extends TestCase
{
    public function testHandle()
    {
        $repository = $this->prophesize(CourseRepositoryInterface::class);
        $eventCollector = $this->prophesize(EventCollector::class);
        $handler = new RemoveCourseHandler($repository->reveal(), $eventCollector->reveal());

        $uuid = $this->prophesize(Uuid::class);

        $course = $this->prophesize(Course::class);
        $repository->findByUuid($uuid->reveal())->willReturn($course->reveal());
        $repository->remove($course->reveal())->shouldBeCalled();

        $command = $this->prophesize(RemoveCourseCommand::class);
        $command->getUuid()->willReturn($uuid->reveal());

        $eventCollector->push(
            'course',
            'removed',
            Argument::that(
                function (CourseRemovedEvent $event) use ($course) {
                    return $course->reveal() === $event->getCourse();
                }
            )
        )->shouldBeCalled();

        $result = $handler->handle($command->reveal());
        $this->assertEquals($course->reveal(), $result);
    }
}
