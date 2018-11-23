<?php

declare(strict_types=1);

namespace Sprungbrett\Bundle\CourseBundle\Model\Schedule\Handler;

use Sprungbrett\Bundle\ContentBundle\Stages;
use Sprungbrett\Bundle\CourseBundle\Model\Course\CourseRepositoryInterface;
use Sprungbrett\Bundle\CourseBundle\Model\Course\Exception\CourseNotPublishedException;
use Sprungbrett\Bundle\CourseBundle\Model\Schedule\Event\SchedulePublishedEvent;
use Sprungbrett\Bundle\CourseBundle\Model\Schedule\Exception\ScheduleNotFoundException;
use Sprungbrett\Bundle\CourseBundle\Model\Schedule\Message\PublishScheduleMessage;
use Sprungbrett\Bundle\CourseBundle\Model\Schedule\ScheduleRepositoryInterface;
use Sprungbrett\Component\MessageCollector\MessageCollector;

class PublishScheduleMessageHandler
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

    public function __construct(
        MessageCollector $messageCollector,
        ScheduleRepositoryInterface $scheduleRepository,
        CourseRepositoryInterface $courseRepository
    ) {
        $this->messageCollector = $messageCollector;
        $this->scheduleRepository = $scheduleRepository;
        $this->courseRepository = $courseRepository;
    }

    public function __invoke(PublishScheduleMessage $message): void
    {
        $locale = $message->getLocale();

        $draftSchedule = $this->scheduleRepository->findById($message->getUuid(), Stages::DRAFT, $locale);
        if (!$draftSchedule) {
            throw new ScheduleNotFoundException($message->getUuid());
        }

        $courseId = $draftSchedule->getCourse()->getUuid();
        $course = $this->courseRepository->findById($courseId, Stages::LIVE, $locale);
        if (!$course) {
            throw new CourseNotPublishedException($courseId);
        }

        $liveSchedule = $this->scheduleRepository->findById($message->getUuid(), Stages::LIVE, $locale);
        if (!$liveSchedule) {
            $liveSchedule = $this->scheduleRepository->create($message->getUuid(), $course, Stages::LIVE, $locale);
        }

        $liveSchedule->setMinimumParticipants($draftSchedule->getMinimumParticipants());
        $liveSchedule->setMaximumParticipants($draftSchedule->getMaximumParticipants());
        $liveSchedule->setPrice($draftSchedule->getPrice());

        $liveSchedule->setName($draftSchedule->getName() ?: '');
        $liveSchedule->setDescription($draftSchedule->getDescription() ?: '');

        $this->messageCollector->push(new SchedulePublishedEvent($message->getUuid(), $message->getLocale()));
    }
}
