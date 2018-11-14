<?php

namespace Sprungbrett\Bundle\CourseBundle\Model;

interface CourseRepositoryInterface
{
    public function create(string $uuid, ?string $locale = null): CourseInterface;

    public function findById(string $uuid, ?string $locale = null): ?CourseInterface;

    public function remove(CourseInterface $course): void;
}
