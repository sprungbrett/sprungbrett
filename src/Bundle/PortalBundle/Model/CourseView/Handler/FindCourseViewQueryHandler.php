<?php

declare(strict_types=1);

namespace Sprungbrett\Bundle\PortalBundle\Model\CourseView\Handler;

use Sprungbrett\Bundle\PortalBundle\Model\CourseView\CourseViewInterface;
use Sprungbrett\Bundle\PortalBundle\Model\CourseView\CourseViewRepositoryInterface;
use Sprungbrett\Bundle\PortalBundle\Model\CourseView\Exception\CourseViewNotFoundException;
use Sprungbrett\Bundle\PortalBundle\Model\CourseView\Query\FindCourseViewQuery;

class FindCourseViewQueryHandler
{
    /**
     * @var CourseViewRepositoryInterface
     */
    private $repository;

    public function __construct(CourseViewRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function __invoke(FindCourseViewQuery $query): CourseViewInterface
    {
        $courseView = $this->repository->findById($query->getUuid(), $query->getLocale());
        if (!$courseView) {
            throw new CourseViewNotFoundException($query->getUuid());
        }

        return $courseView;
    }
}
