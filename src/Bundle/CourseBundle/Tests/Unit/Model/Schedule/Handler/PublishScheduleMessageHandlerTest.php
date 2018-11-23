<?php

declare(strict_types=1);

namespace Sprungbrett\Bundle\CourseBundle\Tests\Unit\Model\Schedule\Handler;

use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Sprungbrett\Bundle\ContentBundle\Stages;
use Sprungbrett\Bundle\CourseBundle\Model\Course\CourseInterface;
use Sprungbrett\Bundle\CourseBundle\Model\Course\CourseRepositoryInterface;
use Sprungbrett\Bundle\CourseBundle\Model\Schedule\Event\SchedulePublishedEvent;
use Sprungbrett\Bundle\CourseBundle\Model\Schedule\Exception\ScheduleNotFoundException;
use Sprungbrett\Bundle\CourseBundle\Model\Schedule\Handler\PublishScheduleMessageHandler;
use Sprungbrett\Bundle\CourseBundle\Model\Schedule\Message\PublishScheduleMessage;
use Sprungbrett\Bundle\CourseBundle\Model\Schedule\ScheduleInterface;
use Sprungbrett\Bundle\CourseBundle\Model\Schedule\ScheduleRepositoryInterface;
use Sprungbrett\Component\MessageCollector\MessageCollector;
use Symfony\Component\Messenger\MessageBusInterface;

class PublishScheduleMessageHandlerTest extends TestCase
{
    /**
     * @var MessageBusInterface
     */
    private $messageBus;

    /**
     * @var MessageCollector
     */
    private $messageCollector;

    /**
     * @var ScheduleRepositoryInterface
     */
    private $scheduleRepository;

    /**
     * @var CourseRepositoryInterface
     */
    private $courseRepository;

    /**
     * @var PublishScheduleMessageHandler
     */
    private $handler;

    /**
     * @var PublishScheduleMessage
     */
    private $message;

    protected function setUp()
    {
        $this->messageBus = $this->prophesize(MessageBusInterface::class);
        $this->messageCollector = $this->prophesize(MessageCollector::class);
        $this->scheduleRepository = $this->prophesize(ScheduleRepositoryInterface::class);
        $this->courseRepository = $this->prophesize(CourseRepositoryInterface::class);

        $this->handler = new PublishScheduleMessageHandler(
            $this->messageCollector->reveal(),
            $this->scheduleRepository->reveal(),
            $this->courseRepository->reveal()
        );

        $this->message = $this->prophesize(PublishScheduleMessage::class);
        $this->message->getUuid()->willReturn('123-123-123');
        $this->message->getLocale()->willReturn('en');
    }

    public function testInvoke(): void
    {
        $course = $this->prophesize(CourseInterface::class);
        $course->getUuid()->willReturn('course-123-123-123');
        $this->courseRepository->findById('course-123-123-123', Stages::LIVE, 'en')->willReturn($course->reveal());

        $draftSchedule = $this->prophesize(ScheduleInterface::class);
        $draftSchedule->getCourse()->willReturn($course->reveal());
        $this->scheduleRepository->findById('123-123-123', Stages::DRAFT, 'en')->willReturn($draftSchedule->reveal());

        $liveSchedule = $this->prophesize(ScheduleInterface::class);
        $liveSchedule->getUuid()->willReturn('123-123-123');
        $this->scheduleRepository->findById('123-123-123', Stages::LIVE, 'en')->willReturn($liveSchedule->reveal());

        $draftSchedule->getName()->willReturn('Sprungbrett');
        $liveSchedule->setName('Sprungbrett')->shouldBeCalled();

        $draftSchedule->getDescription()->willReturn('Sprungbrett is awesome');
        $liveSchedule->setDescription('Sprungbrett is awesome')->shouldBeCalled();

        $draftSchedule->getMinimumParticipants()->willReturn(5);
        $liveSchedule->setMinimumParticipants(5)->shouldBeCalled();

        $draftSchedule->getMaximumParticipants()->willReturn(15);
        $liveSchedule->setMaximumParticipants(15)->shouldBeCalled();

        $draftSchedule->getPrice()->willReturn(15.5);
        $liveSchedule->setPrice(15.5)->shouldBeCalled();

        $this->messageCollector->push(
            Argument::that(
                function (SchedulePublishedEvent $event) {
                    $this->assertEquals('123-123-123', $event->getUuid());
                    $this->assertEquals('en', $event->getLocale());

                    return true;
                }
            )
        )->shouldBeCalled();

        $this->handler->__invoke($this->message->reveal());
    }

    public function testInvokeCreate(): void
    {
        $course = $this->prophesize(CourseInterface::class);
        $course->getUuid()->willReturn('course-123-123-123');
        $this->courseRepository->findById('course-123-123-123', Stages::LIVE, 'en')->willReturn($course->reveal());

        $draftSchedule = $this->prophesize(ScheduleInterface::class);
        $draftSchedule->getCourse()->willReturn($course->reveal());
        $this->scheduleRepository->findById('123-123-123', Stages::DRAFT, 'en')->willReturn($draftSchedule->reveal());

        $liveSchedule = $this->prophesize(ScheduleInterface::class);
        $liveSchedule->getUuid()->willReturn('123-123-123');
        $this->scheduleRepository->findById('123-123-123', Stages::LIVE, 'en')->willReturn(null);
        $this->scheduleRepository->create('123-123-123', $course->reveal(), Stages::LIVE, 'en')
            ->willReturn($liveSchedule->reveal());

        $draftSchedule->getName()->willReturn('Sprungbrett');
        $liveSchedule->setName('Sprungbrett')->shouldBeCalled();

        $draftSchedule->getDescription()->willReturn('Sprungbrett is awesome');
        $liveSchedule->setDescription('Sprungbrett is awesome')->shouldBeCalled();

        $draftSchedule->getMinimumParticipants()->willReturn(5);
        $liveSchedule->setMinimumParticipants(5)->shouldBeCalled();

        $draftSchedule->getMaximumParticipants()->willReturn(15);
        $liveSchedule->setMaximumParticipants(15)->shouldBeCalled();

        $draftSchedule->getPrice()->willReturn(15.5);
        $liveSchedule->setPrice(15.5)->shouldBeCalled();

        $this->messageCollector->push(
            Argument::that(
                function (SchedulePublishedEvent $event) use ($draftSchedule) {
                    $this->assertEquals('123-123-123', $event->getUuid());
                    $this->assertEquals('en', $event->getLocale());

                    return true;
                }
            )
        )->shouldBeCalled();

        $this->handler->__invoke($this->message->reveal());
    }

    public function testInvokeNotFound(): void
    {
        $this->expectException(ScheduleNotFoundException::class);

        $this->scheduleRepository->findById('123-123-123', Stages::DRAFT, 'en')->willReturn(null);

        $this->messageCollector->push(Argument::any())->shouldNotBeCalled();

        $this->handler->__invoke($this->message->reveal());
    }
}
