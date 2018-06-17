<?php

namespace Sprungbrett\Component\Course\Model;

use Sprungbrett\Component\Course\Model\Exception\CourseNotFoundException;
use Sprungbrett\Component\Translation\Model\Localization;
use Sprungbrett\Component\Uuid\Model\Uuid;

interface CourseRepositoryInterface
{
    public function create(?Localization $localization = null, ?Uuid $uuid = null): CourseInterface;

    /**
     * @throws CourseNotFoundException
     */
    public function findByUuid(Uuid $uuid, ?Localization $localization = null): CourseInterface;

    public function remove(CourseInterface $course): void;
}
