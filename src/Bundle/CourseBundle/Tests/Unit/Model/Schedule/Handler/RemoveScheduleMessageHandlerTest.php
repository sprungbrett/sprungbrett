<?php

declare(strict_types=1);

namespace Sprungbrett\Bundle\CourseBundle\Tests\Unit\Model\Schedule\Handler;

use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Sprungbrett\Bundle\CourseBundle\Model\Schedule\Event\ScheduleRemovedEvent;
use Sprungbrett\Bundle\CourseBundle\Model\Schedule\Handler\RemoveScheduleMessageHandler;
use Sprungbrett\Bundle\CourseBundle\Model\Schedule\Message\RemoveScheduleMessage;
use Sprungbrett\Bundle\CourseBundle\Model\Schedule\ScheduleInterface;
use Sprungbrett\Bundle\CourseBundle\Model\Schedule\ScheduleRepositoryInterface;
use Sprungbrett\Component\MessageCollector\MessageCollector;
use Symfony\Component\Messenger\MessageBusInterface;

class RemoveScheduleMessageHandlerTest extends TestCase
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
     * @var RemoveScheduleMessageHandler
     */
    private $handler;

    /**
     * @var RemoveScheduleMessage
     */
    private $message;

    protected function setUp()
    {
        $this->messageBus = $this->prophesize(MessageBusInterface::class);
        $this->messageCollector = $this->prophesize(MessageCollector::class);
        $this->scheduleRepository = $this->prophesize(ScheduleRepositoryInterface::class);

        $this->handler = new RemoveScheduleMessageHandler(
            $this->messageCollector->reveal(),
            $this->scheduleRepository->reveal()
        );

        $this->message = $this->prophesize(RemoveScheduleMessage::class);
        $this->message->getUuid()->willReturn('123-123-123');
    }

    public function testInvoke(): void
    {
        $draftSchedule = $this->prophesize(ScheduleInterface::class);
        $liveSchedule = $this->prophesize(ScheduleInterface::class);

        $this->scheduleRepository->findAllById('123-123-123')
            ->willReturn([$draftSchedule->reveal(), $liveSchedule->reveal()]);

        $this->scheduleRepository->remove($draftSchedule->reveal())->shouldBeCalled();
        $this->scheduleRepository->remove($liveSchedule->reveal())->shouldBeCalled();

        $this->messageCollector->push(
            Argument::that(
                function (ScheduleRemovedEvent $event) use ($draftSchedule) {
                    $this->assertEquals('123-123-123', $event->getUuid());

                    return true;
                }
            )
        )->shouldBeCalled();

        $this->handler->__invoke($this->message->reveal());
    }
}
