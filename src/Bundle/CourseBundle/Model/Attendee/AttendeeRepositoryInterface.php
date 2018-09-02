<?php

namespace Sprungbrett\Bundle\CourseBundle\Model\Attendee;

use Sprungbrett\Component\Translation\Model\Localization;

interface AttendeeRepositoryInterface
{
    public function findOrCreateAttendeeById(int $id, ?Localization $localization = null): AttendeeInterface;
}
