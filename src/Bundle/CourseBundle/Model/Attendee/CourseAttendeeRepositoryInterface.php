<?php

namespace Sprungbrett\Bundle\CourseBundle\Model\Attendee;

use Sprungbrett\Component\Translation\Model\Localization;

interface CourseAttendeeRepositoryInterface
{
    public function findOrCreateCourseAttendeeById(
        int $attendeeId,
        string $courseId,
        ?Localization $localization = null
    ): CourseAttendeeInterface;

    public function countByCourse(string $courseId): int;

    public function exists(int $attendeeId, string $courseId): bool;
}
