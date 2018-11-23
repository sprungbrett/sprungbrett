<?php

declare(strict_types=1);

namespace Sprungbrett\Bundle\PortalBundle\Model\CourseView\Handler;

use Sprungbrett\Bundle\ContentBundle\Stages;
use Sprungbrett\Bundle\CourseBundle\Model\Schedule\Event\SchedulePublishedEvent;
use Sprungbrett\Bundle\CourseBundle\Model\Schedule\Exception\ScheduleNotFoundException;
use Sprungbrett\Bundle\CourseBundle\Model\Schedule\ScheduleRepositoryInterface;
use Sprungbrett\Bundle\PortalBundle\Model\CourseView\CourseViewRepositoryInterface;
use Sprungbrett\Bundle\PortalBundle\Model\CourseView\Exception\CourseViewNotFoundException;

class SchedulePublishedEventHandler
{
    /**
     * @var CourseViewRepositoryInterface
     */
    private $courseViewRepository;

    /**
     * @var ScheduleRepositoryInterface
     */
    private $scheduleRepository;

    public function __construct(
        CourseViewRepositoryInterface $courseViewRepository,
        ScheduleRepositoryInterface $scheduleRepository
    ) {
        $this->courseViewRepository = $courseViewRepository;
        $this->scheduleRepository = $scheduleRepository;
    }

    public function __invoke(SchedulePublishedEvent $event): void
    {
        $schedule = $this->scheduleRepository->findById($event->getUuid(), Stages::LIVE, $event->getLocale());
        if (!$schedule) {
            throw new ScheduleNotFoundException($event->getUuid());
        }

        $courseView = $this->courseViewRepository->findById($schedule->getCourse()->getUuid(), $event->getLocale());
        if (!$courseView) {
            throw new CourseViewNotFoundException($schedule->getCourse()->getUuid());
        }

        $courseView->addSchedule($schedule);
    }
}
