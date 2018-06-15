<?php

namespace Sprungbrett\Component\Course\Model;

use Sprungbrett\Component\Course\Model\Exception\CourseNotFoundException;
use Sprungbrett\Component\Uuid\Model\Uuid;

interface CourseRepositoryInterface
{
    public function create(?Uuid $uuid = null): CourseInterface;

    /**
     * @throws CourseNotFoundException
     */
    public function findByUuid(Uuid $uuid): CourseInterface;

    public function remove(CourseInterface $course): void;
}
