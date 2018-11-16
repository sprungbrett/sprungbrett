<?php

declare(strict_types=1);

namespace Sprungbrett\Bundle\PortalBundle\Model\CourseView\Handler;

use Sprungbrett\Bundle\PortalBundle\Model\CourseView\CourseViewInterface;
use Sprungbrett\Bundle\PortalBundle\Model\CourseView\CourseViewRepositoryInterface;
use Sprungbrett\Bundle\PortalBundle\Model\CourseView\Query\ListCourseViewQuery;

class ListCourseViewQueryHandler
{
    /**
     * @var CourseViewRepositoryInterface
     */
    private $repository;

    public function __construct(CourseViewRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @return CourseViewInterface[]
     */
    public function __invoke(ListCourseViewQuery $query): array
    {
        return $this->repository->list($query->getPage(), $query->getPageSize());
    }
}
