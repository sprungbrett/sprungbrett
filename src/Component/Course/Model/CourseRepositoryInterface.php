<?php

namespace Sprungbrett\Component\Course\Model;

use Sprungbrett\Component\Course\Model\Exception\CourseNotFoundException;
use Sprungbrett\Component\Uuid\Model\Uuid;

interface CourseRepositoryInterface
{
    public function create(?Uuid $uuid = null): Course;

    /**
     * @throws CourseNotFoundException
     */
    public function findByUuid(Uuid $uuid): Course;

    public function remove(Course $course): void;
}
