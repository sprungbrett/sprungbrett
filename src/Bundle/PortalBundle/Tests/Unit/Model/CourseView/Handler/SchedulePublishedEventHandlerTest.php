<?php

declare(strict_types=1);

namespace Sprungbrett\Bundle\PortalBundle\Tests\Unit\Model\CourseView\Handler;

use PHPUnit\Framework\TestCase;
use Sprungbrett\Bundle\ContentBundle\Stages;
use Sprungbrett\Bundle\CourseBundle\Model\Course\CourseInterface;
use Sprungbrett\Bundle\CourseBundle\Model\Schedule\Event\SchedulePublishedEvent;
use Sprungbrett\Bundle\CourseBundle\Model\Schedule\Exception\ScheduleNotFoundException;
use Sprungbrett\Bundle\CourseBundle\Model\Schedule\ScheduleInterface;
use Sprungbrett\Bundle\CourseBundle\Model\Schedule\ScheduleRepositoryInterface;
use Sprungbrett\Bundle\PortalBundle\Model\CourseView\CourseViewInterface;
use Sprungbrett\Bundle\PortalBundle\Model\CourseView\CourseViewRepositoryInterface;
use Sprungbrett\Bundle\PortalBundle\Model\CourseView\Handler\SchedulePublishedEventHandler;

class SchedulePublishedEventHandlerTest extends TestCase
{
    /**
     * @var CourseViewRepositoryInterface
     */
    private $courseViewRepository;

    /**
     * @var ScheduleRepositoryInterface
     */
    private $scheduleRepository;

    /**
     * @var SchedulePublishedEventHandler
     */
    private $handler;

    /**
     * @var SchedulePublishedEvent
     */
    private $message;

    protected function setUp()
    {
        $this->courseViewRepository = $this->prophesize(CourseViewRepositoryInterface::class);
        $this->scheduleRepository = $this->prophesize(ScheduleRepositoryInterface::class);

        $this->handler = new SchedulePublishedEventHandler(
            $this->courseViewRepository->reveal(),
            $this->scheduleRepository->reveal()
        );

        $this->message = $this->prophesize(SchedulePublishedEvent::class);
        $this->message->getUuid()->willReturn('123-123-123');
        $this->message->getLocale()->willReturn('en');
    }

    public function testInvoke(): void
    {
        $course = $this->prophesize(CourseInterface::class);
        $course->getUuid()->willReturn('course-123-123-123');

        $schedule = $this->prophesize(ScheduleInterface::class);
        $schedule->getCourse()->willReturn($course->reveal());
        $this->scheduleRepository->findById('123-123-123', Stages::LIVE, 'en')->willReturn($schedule->reveal());

        $courseView = $this->prophesize(CourseViewInterface::class);
        $this->courseViewRepository->findById('course-123-123-123', 'en')->willReturn($courseView->reveal());

        $courseView->addSchedule($schedule->reveal())->shouldBeCalled();

        $this->handler->__invoke($this->message->reveal());
    }

    public function testInvokeNotFound(): void
    {
        $this->expectException(ScheduleNotFoundException::class);

        $this->scheduleRepository->findById('123-123-123', Stages::LIVE, 'en')->willReturn(null);

        $this->handler->__invoke($this->message->reveal());
    }
}
