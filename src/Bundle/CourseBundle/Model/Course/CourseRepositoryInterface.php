<?php

declare(strict_types=1);

namespace Sprungbrett\Bundle\CourseBundle\Model\Course;

interface CourseRepositoryInterface
{
    public function create(string $uuid, string $stage, ?string $locale = null): CourseInterface;

    public function findById(string $uuid, string $stage, ?string $locale = null): ?CourseInterface;

    /**
     * @return CourseInterface[]
     */
    public function findAllById(string $uuid): array;

    public function remove(CourseInterface $course): void;
}
