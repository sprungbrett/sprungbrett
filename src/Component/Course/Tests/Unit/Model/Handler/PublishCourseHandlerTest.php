<?php

namespace Sprungbrett\Component\Course\Tests\Unit\Model\Handler;

use PHPUnit\Framework\TestCase;
use Sprungbrett\Component\Course\Model\Command\PublishCourseCommand;
use Sprungbrett\Component\Course\Model\Course;
use Sprungbrett\Component\Course\Model\CourseInterface;
use Sprungbrett\Component\Course\Model\CourseRepositoryInterface;
use Sprungbrett\Component\Course\Model\Exception\CourseTransitionNotAvailableException;
use Sprungbrett\Component\Course\Model\Handler\PublishCourseHandler;
use Sprungbrett\Component\EventCollector\EventCollector;
use Sprungbrett\Component\Translation\Model\Localization;
use Sprungbrett\Component\Uuid\Model\Uuid;
use Symfony\Component\Workflow\Marking;
use Symfony\Component\Workflow\Workflow;

class PublishCourseHandlerTest extends TestCase
{
    public function testHandle()
    {
        $repository = $this->prophesize(CourseRepositoryInterface::class);
        $workflow = $this->prophesize(Workflow::class);
        $eventCollector = $this->prophesize(EventCollector::class);
        $handler = new PublishCourseHandler($repository->reveal(), $workflow->reveal(), $eventCollector->reveal());

        $uuid = $this->prophesize(Uuid::class);
        $localization = $this->prophesize(Localization::class);

        $course = $this->prophesize(Course::class);
        $repository->findByUuid($uuid->reveal(), $localization->reveal())->willReturn($course->reveal());

        $command = $this->prophesize(PublishCourseCommand::class);
        $command->getUuid()->willReturn($uuid->reveal());
        $command->getLocalization()->willReturn($localization->reveal());

        $marking = $this->prophesize(Marking::class);
        $workflow->can($course->reveal(), CourseInterface::TRANSITION_PUBLISH)->shouldBeCalled()->willReturn(true);
        $workflow->apply($course->reveal(), CourseInterface::TRANSITION_PUBLISH)
            ->shouldBeCalled()
            ->willReturn($marking->reveal());

        $result = $handler->handle($command->reveal());
        $this->assertEquals($course->reveal(), $result);
    }

    public function testHandleNotAvailable()
    {
        $this->expectException(CourseTransitionNotAvailableException::class);

        $repository = $this->prophesize(CourseRepositoryInterface::class);
        $workflow = $this->prophesize(Workflow::class);
        $eventCollector = $this->prophesize(EventCollector::class);
        $handler = new PublishCourseHandler($repository->reveal(), $workflow->reveal(), $eventCollector->reveal());

        $uuid = $this->prophesize(Uuid::class);
        $localization = $this->prophesize(Localization::class);

        $course = $this->prophesize(Course::class);
        $course->getId()->willReturn('123-123-123');
        $repository->findByUuid($uuid->reveal(), $localization->reveal())->willReturn($course->reveal());

        $command = $this->prophesize(PublishCourseCommand::class);
        $command->getUuid()->willReturn($uuid->reveal());
        $command->getLocalization()->willReturn($localization->reveal());

        $workflow->can($course->reveal(), CourseInterface::TRANSITION_PUBLISH)->shouldBeCalled()->willReturn(false);
        $workflow->apply($course->reveal(), CourseInterface::TRANSITION_PUBLISH)->shouldNotBeCalled();

        $handler->handle($command->reveal());
    }
}
