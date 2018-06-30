<?php

namespace Sprungbrett\Bundle\CourseBundle\Tests\Unit\Model\Course\Handler;

use PHPUnit\Framework\TestCase;
use Sprungbrett\Bundle\CourseBundle\Model\Course\Command\UnpublishCourseCommand;
use Sprungbrett\Bundle\CourseBundle\Model\Course\CourseInterface;
use Sprungbrett\Bundle\CourseBundle\Model\Course\CourseRepositoryInterface;
use Sprungbrett\Bundle\CourseBundle\Model\Course\Exception\CourseTransitionNotAvailableException;
use Sprungbrett\Bundle\CourseBundle\Model\Course\Handler\UnpublishCourseHandler;
use Sprungbrett\Component\EventCollector\EventCollector;
use Sprungbrett\Component\Translation\Model\Localization;
use Symfony\Component\Workflow\Marking;
use Symfony\Component\Workflow\Workflow;

class UnpublishCourseHandlerTest extends TestCase
{
    public function testHandle()
    {
        $repository = $this->prophesize(CourseRepositoryInterface::class);
        $workflow = $this->prophesize(Workflow::class);
        $eventCollector = $this->prophesize(EventCollector::class);
        $handler = new UnpublishCourseHandler($repository->reveal(), $workflow->reveal(), $eventCollector->reveal());

        $localization = $this->prophesize(Localization::class);

        $course = $this->prophesize(CourseInterface::class);
        $repository->findById('123-123-123', $localization->reveal())->willReturn($course->reveal());

        $command = $this->prophesize(UnpublishCourseCommand::class);
        $command->getId()->willReturn('123-123-123');
        $command->getLocalization()->willReturn($localization->reveal());

        $marking = $this->prophesize(Marking::class);
        $workflow->can($course->reveal(), CourseInterface::TRANSITION_UNPUBLISH)->shouldBeCalled()->willReturn(true);
        $workflow->apply($course->reveal(), CourseInterface::TRANSITION_UNPUBLISH)
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
        $handler = new UnpublishCourseHandler($repository->reveal(), $workflow->reveal(), $eventCollector->reveal());

        $localization = $this->prophesize(Localization::class);

        $course = $this->prophesize(CourseInterface::class);
        $course->getId()->willReturn('123-123-123');
        $repository->findById('123-123-123', $localization->reveal())->willReturn($course->reveal());

        $command = $this->prophesize(UnpublishCourseCommand::class);
        $command->getId()->willReturn('123-123-123');
        $command->getLocalization()->willReturn($localization->reveal());

        $workflow->can($course->reveal(), CourseInterface::TRANSITION_UNPUBLISH)->shouldBeCalled()->willReturn(false);
        $workflow->apply($course->reveal(), CourseInterface::TRANSITION_UNPUBLISH)->shouldNotBeCalled();

        $handler->handle($command->reveal());
    }
}
