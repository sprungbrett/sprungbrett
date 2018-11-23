<?php

declare(strict_types=1);

namespace Sprungbrett\Bundle\CourseBundle\Tests\Unit\Model\Schedule\Handler;

use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Sprungbrett\Bundle\ContentBundle\Stages;
use Sprungbrett\Bundle\CourseBundle\Model\Course\CourseRepositoryInterface;
use Sprungbrett\Bundle\CourseBundle\Model\Schedule\Event\ScheduleModifiedEvent;
use Sprungbrett\Bundle\CourseBundle\Model\Schedule\Handler\ModifyScheduleMessageHandler;
use Sprungbrett\Bundle\CourseBundle\Model\Schedule\Message\ModifyScheduleMessage;
use Sprungbrett\Bundle\CourseBundle\Model\Schedule\ScheduleInterface;
use Sprungbrett\Bundle\CourseBundle\Model\Schedule\ScheduleRepositoryInterface;
use Sprungbrett\Component\MessageCollector\MessageCollector;

class ModifyScheduleMessageHandlerTest extends TestCase
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
     * @var ModifyScheduleMessageHandler
     */
    private $handler;

    /**
     * @var ModifyScheduleMessage
     */
    private $message;

    protected function setUp()
    {
        $this->messageCollector = $this->prophesize(MessageCollector::class);
        $this->scheduleRepository = $this->prophesize(ScheduleRepositoryInterface::class);
        $this->courseRepository = $this->prophesize(CourseRepositoryInterface::class);

        $this->handler = new ModifyScheduleMessageHandler(
            $this->messageCollector->reveal(),
            $this->scheduleRepository->reveal()
        );

        $this->message = $this->prophesize(ModifyScheduleMessage::class);
        $this->message->getUuid()->willReturn('123-123-123');
        $this->message->getLocale()->willReturn('en');
        $this->message->getName()->willReturn('Sprungbrett');
        $this->message->getDescription()->willReturn('Sprungbrett is awesome');
        $this->message->getMinimumParticipants()->willReturn(5);
        $this->message->getMaximumParticipants()->willReturn(15);
        $this->message->getPrice()->willReturn(15.5);
    }

    public function testInvoke(): void
    {
        $schedule = $this->prophesize(ScheduleInterface::class);
        $this->scheduleRepository->findById('123-123-123', Stages::DRAFT, 'en')
            ->willReturn($schedule->reveal());

        $schedule->setName('Sprungbrett')->shouldBeCalled();
        $schedule->setDescription('Sprungbrett is awesome')->shouldBeCalled();
        $schedule->setMinimumParticipants(5)->shouldBeCalled();
        $schedule->setMaximumParticipants(15)->shouldBeCalled();
        $schedule->setPrice(15.5)->shouldBeCalled();

        $this->messageCollector->push(
            Argument::that(
                function (ScheduleModifiedEvent $event) use ($schedule) {
                    $this->assertEquals($schedule->reveal(), $event->getSchedule());

                    return true;
                }
            )
        )->shouldBeCalled();

        $this->handler->__invoke($this->message->reveal());
    }
}
