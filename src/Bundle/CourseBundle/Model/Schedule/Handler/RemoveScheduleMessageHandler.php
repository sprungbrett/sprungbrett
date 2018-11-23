<?php

declare(strict_types=1);

namespace Sprungbrett\Bundle\CourseBundle\Model\Schedule\Handler;

use Sprungbrett\Bundle\CourseBundle\Model\Schedule\Event\ScheduleRemovedEvent;
use Sprungbrett\Bundle\CourseBundle\Model\Schedule\Message\RemoveScheduleMessage;
use Sprungbrett\Bundle\CourseBundle\Model\Schedule\ScheduleRepositoryInterface;
use Sprungbrett\Component\MessageCollector\MessageCollector;

class RemoveScheduleMessageHandler
{
    /**
     * @var MessageCollector
     */
    private $messageCollector;

    /**
     * @var ScheduleRepositoryInterface
     */
    private $scheduleRepository;

    public function __construct(
        MessageCollector $messageCollector,
        ScheduleRepositoryInterface $scheduleRepository
    ) {
        $this->messageCollector = $messageCollector;
        $this->scheduleRepository = $scheduleRepository;
    }

    public function __invoke(RemoveScheduleMessage $message): void
    {
        $schedules = $this->scheduleRepository->findAllById($message->getUuid());
        foreach ($schedules as $schedule) {
            $this->scheduleRepository->remove($schedule);
        }

        $this->messageCollector->push(new ScheduleRemovedEvent($message->getUuid()));
    }
}
