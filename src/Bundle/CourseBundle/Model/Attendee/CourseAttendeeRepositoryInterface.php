<?php

namespace Sprungbrett\Bundle\CourseBundle\Model\Attendee;

interface CourseAttendeeRepositoryInterface
{
    public function findOrCreateCourseAttendeeById(int $attendeeId, string $courseId): CourseAttendeeInterface;

    public function countByCourse(string $courseId): int;

    public function exists(int $attendeeId, string $courseId): bool;
}
