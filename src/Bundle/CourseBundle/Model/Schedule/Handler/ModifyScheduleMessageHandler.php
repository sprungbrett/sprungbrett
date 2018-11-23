<?php

declare(strict_types=1);

namespace Sprungbrett\Bundle\CourseBundle\Model\Schedule\Handler;

use Sprungbrett\Bundle\ContentBundle\Stages;
use Sprungbrett\Bundle\CourseBundle\Model\Schedule\Event\ScheduleModifiedEvent;
use Sprungbrett\Bundle\CourseBundle\Model\Schedule\Exception\ScheduleNotFoundException;
use Sprungbrett\Bundle\CourseBundle\Model\Schedule\Message\ModifyScheduleMessage;
use Sprungbrett\Bundle\CourseBundle\Model\Schedule\ScheduleRepositoryInterface;
use Sprungbrett\Component\MessageCollector\MessageCollector;

class ModifyScheduleMessageHandler
{
    /**
     * @var MessageCollector
     */
    private $messageCollector;

    /**
     * @var ScheduleRepositoryInterface
     */
    private $scheduleRepository;

    public function __construct(MessageCollector $messageCollector, ScheduleRepositoryInterface $scheduleRepository)
    {
        $this->messageCollector = $messageCollector;
        $this->scheduleRepository = $scheduleRepository;
    }

    public function __invoke(ModifyScheduleMessage $message): void
    {
        $schedule = $this->scheduleRepository->findById($message->getUuid(), Stages::DRAFT, $message->getLocale());
        if (!$schedule) {
            throw new ScheduleNotFoundException($message->getUuid());
        }

        $schedule->setMinimumParticipants($message->getMinimumParticipants());
        $schedule->setMaximumParticipants($message->getMaximumParticipants());
        $schedule->setPrice($message->getPrice());

        $schedule->setName($message->getName());
        $schedule->setDescription($message->getDescription());

        $this->messageCollector->push(new ScheduleModifiedEvent($schedule));
    }
}
