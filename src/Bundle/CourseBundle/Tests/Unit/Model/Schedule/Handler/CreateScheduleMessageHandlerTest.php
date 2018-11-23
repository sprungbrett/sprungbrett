<?php

declare(strict_types=1);

namespace Sprungbrett\Bundle\CourseBundle\Tests\Unit\Model\Schedule\Handler;

use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Sprungbrett\Bundle\ContentBundle\Stages;
use Sprungbrett\Bundle\CourseBundle\Model\Course\CourseInterface;
use Sprungbrett\Bundle\CourseBundle\Model\Course\CourseRepositoryInterface;
use Sprungbrett\Bundle\CourseBundle\Model\Schedule\Event\ScheduleCreatedEvent;
use Sprungbrett\Bundle\CourseBundle\Model\Schedule\Handler\CreateScheduleMessageHandler;
use Sprungbrett\Bundle\CourseBundle\Model\Schedule\Message\CreateScheduleMessage;
use Sprungbrett\Bundle\CourseBundle\Model\Schedule\ScheduleInterface;
use Sprungbrett\Bundle\CourseBundle\Model\Schedule\ScheduleRepositoryInterface;
use Sprungbrett\Component\MessageCollector\MessageCollector;

class CreateScheduleMessageHandlerTest extends TestCase
{
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
     * @var CreateScheduleMessageHandler
     */
    private $handler;

    /**
     * @var CreateScheduleMessage
     */
    private $message;

    protected function setUp()
    {
        $this->messageCollector = $this->prophesize(MessageCollector::class);
        $this->scheduleRepository = $this->prophesize(ScheduleRepositoryInterface::class);
        $this->courseRepository = $this->prophesize(CourseRepositoryInterface::class);

        $this->handler = new CreateScheduleMessageHandler(
            $this->messageCollector->reveal(),
            $this->scheduleRepository->reveal(),
            $this->courseRepository->reveal()
        );

        $this->message = $this->prophesize(CreateScheduleMessage::class);
        $this->message->getUuid()->willReturn('123-123-123');
        $this->message->getLocale()->willReturn('en');
        $this->message->getName()->willReturn('Sprungbrett');
        $this->message->getDescription()->willReturn('Sprungbrett is awesome');
        $this->message->getMinimumParticipants()->willReturn(5);
        $this->message->getMaximumParticipants()->willReturn(15);
        $this->message->getPrice()->willReturn(15.5);
        $this->message->getCourse()->willReturn('course-123-123-123');
    }

    public function testInvoke(): void
    {
        $course = $this->prophesize(CourseInterface::class);
        $this->courseRepository->findById('course-123-123-123', Stages::DRAFT, 'en')->willReturn($course->reveal());

        $schedule = $this->prophesize(ScheduleInterface::class);
        $this->scheduleRepository->create('123-123-123', $course->reveal(), Stages::DRAFT, 'en')
            ->willReturn($schedule->reveal());

        $schedule->setName('Sprungbrett')->shouldBeCalled();
        $schedule->setDescription('Sprungbrett is awesome')->shouldBeCalled();
        $schedule->setMinimumParticipants(5)->shouldBeCalled();
        $schedule->setMaximumParticipants(15)->shouldBeCalled();
        $schedule->setPrice(15.5)->shouldBeCalled();

        $this->messageCollector->push(
            Argument::that(
                function (ScheduleCreatedEvent $event) use ($schedule) {
                    $this->assertEquals($schedule->reveal(), $event->getSchedule());

                    return true;
                }
            )
        )->shouldBeCalled();

        $this->handler->__invoke($this->message->reveal());
    }
}
