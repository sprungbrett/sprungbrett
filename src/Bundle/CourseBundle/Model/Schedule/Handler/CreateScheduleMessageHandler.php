<?php

declare(strict_types=1);

namespace Sprungbrett\Bundle\CourseBundle\Model\Schedule\Handler;

use Sprungbrett\Bundle\ContentBundle\Stages;
use Sprungbrett\Bundle\CourseBundle\Model\Course\CourseRepositoryInterface;
use Sprungbrett\Bundle\CourseBundle\Model\Course\Exception\CourseNotFoundException;
use Sprungbrett\Bundle\CourseBundle\Model\Schedule\Event\ScheduleCreatedEvent;
use Sprungbrett\Bundle\CourseBundle\Model\Schedule\Message\CreateScheduleMessage;
use Sprungbrett\Bundle\CourseBundle\Model\Schedule\ScheduleRepositoryInterface;
use Sprungbrett\Component\MessageCollector\MessageCollector;

class CreateScheduleMessageHandler
{
    /**
     * @var MessageCollector
     */
    private $messageCollector;

    /**
     * @var ScheduleRepositoryInterface
     */
    private $repository;

    /**
     * @var CourseRepositoryInterface
     */
    private $courseRepository;

    public function __construct(
        MessageCollector $messageCollector,
        ScheduleRepositoryInterface $repository,
        CourseRepositoryInterface $courseRepository
    ) {
        $this->messageCollector = $messageCollector;
        $this->repository = $repository;
        $this->courseRepository = $courseRepository;
    }

    public function __invoke(CreateScheduleMessage $message): void
    {
        $course = $this->courseRepository->findById($message->getCourse(), Stages::DRAFT, $message->getLocale());
        if (!$course) {
            throw new CourseNotFoundException($message->getCourse());
        }

        $schedule = $this->repository->create($message->getUuid(), $course, Stages::DRAFT, $message->getLocale());
        $schedule->setMinimumParticipants($message->getMinimumParticipants());
        $schedule->setMaximumParticipants($message->getMaximumParticipants());
        $schedule->setPrice($message->getPrice());

        $schedule->setName($message->getName());
        $schedule->setDescription($message->getDescription());

        $this->messageCollector->push(new ScheduleCreatedEvent($schedule));
    }
}
