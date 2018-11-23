<?php

declare(strict_types=1);

namespace Sprungbrett\Bundle\CourseBundle\Model\Schedule\Handler;

use Sprungbrett\Bundle\CourseBundle\Model\Schedule\Exception\ScheduleNotFoundException;
use Sprungbrett\Bundle\CourseBundle\Model\Schedule\Query\FindScheduleQuery;
use Sprungbrett\Bundle\CourseBundle\Model\Schedule\ScheduleInterface;
use Sprungbrett\Bundle\CourseBundle\Model\Schedule\ScheduleRepositoryInterface;

class FindScheduleQueryHandler
{
    /**
     * @var ScheduleRepositoryInterface
     */
    private $scheduleRepository;

    public function __construct(ScheduleRepositoryInterface $scheduleRepository)
    {
        $this->scheduleRepository = $scheduleRepository;
    }

    public function __invoke(FindScheduleQuery $query): ScheduleInterface
    {
        $schedule = $this->scheduleRepository->findById($query->getUuid(), $query->getStage(), $query->getLocale());
        if (!$schedule) {
            throw new ScheduleNotFoundException($query->getUuid());
        }

        return $schedule;
    }
}
