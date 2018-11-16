<?php

declare(strict_types=1);

namespace Sprungbrett\Bundle\PortalBundle\Model\CourseView;

interface CourseViewRepositoryInterface
{
    public function create(string $uuid, string $locale): CourseViewInterface;

    public function findById(string $uuid, string $locale): ?CourseViewInterface;

    /**
     * @return CourseViewInterface[]
     */
    public function list(int $page, int $pageSize): array;
}
