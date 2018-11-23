<?php

declare(strict_types=1);

namespace Sprungbrett\Bundle\CourseBundle\Model\Schedule;

use Sprungbrett\Bundle\CourseBundle\Model\Course\CourseInterface;

interface ScheduleRepositoryInterface
{
    public function create(
        string $uuid,
        CourseInterface $course,
        string $stage,
        ?string $locale = null
    ): ScheduleInterface;

    public function findById(string $uuid, string $stage, ?string $locale = null): ?ScheduleInterface;

    /**
     * @return ScheduleInterface[]
     */
    public function findAllById(string $uuid): array;

    public function remove(ScheduleInterface $schedule): void;
}
