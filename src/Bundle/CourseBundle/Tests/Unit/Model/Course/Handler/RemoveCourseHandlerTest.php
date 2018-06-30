<?php

namespace Sprungbrett\Bundle\CourseBundle\Tests\Unit\Model\Course\Handler;

use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Sprungbrett\Bundle\CourseBundle\Model\Course\Command\RemoveCourseCommand;
use Sprungbrett\Bundle\CourseBundle\Model\Course\CourseInterface;
use Sprungbrett\Bundle\CourseBundle\Model\Course\CourseRepositoryInterface;
use Sprungbrett\Bundle\CourseBundle\Model\Course\Event\CourseRemovedEvent;
use Sprungbrett\Bundle\CourseBundle\Model\Course\Handler\RemoveCourseHandler;
use Sprungbrett\Component\EventCollector\EventCollector;

class RemoveCourseHandlerTest extends TestCase
{
    public function testHandle()
    {
        $repository = $this->prophesize(CourseRepositoryInterface::class);
        $eventCollector = $this->prophesize(EventCollector::class);
        $handler = new RemoveCourseHandler($repository->reveal(), $eventCollector->reveal());

        $course = $this->prophesize(CourseInterface::class);
        $repository->findById('123-123-123')->willReturn($course->reveal());
        $repository->remove($course->reveal())->shouldBeCalled();

        $command = $this->prophesize(RemoveCourseCommand::class);
        $command->getId()->willReturn('123-123-123');

        $eventCollector->push(
            CourseRemovedEvent::COMPONENT_NAME,
            CourseRemovedEvent::NAME,
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
