<?php

namespace Sprungbrett\Bundle\CourseBundle\Model\Handler;

use Sprungbrett\Bundle\CourseBundle\Model\CourseInterface;
use Sprungbrett\Bundle\CourseBundle\Model\CourseRepositoryInterface;
use Sprungbrett\Bundle\CourseBundle\Model\Exception\CourseNotFoundException;
use Sprungbrett\Bundle\CourseBundle\Model\Query\FindCourseQuery;

class FindCourseQueryHandler
{
    /**
     * @var CourseRepositoryInterface
     */
    private $courseRepository;

    public function __construct(CourseRepositoryInterface $courseRepository)
    {
        $this->courseRepository = $courseRepository;
    }

    public function __invoke(FindCourseQuery $query): CourseInterface
    {
        $course = $this->courseRepository->findById($query->getUuid(), $query->getLocale());
        if (!$course) {
            throw new CourseNotFoundException($query->getUuid());
        }

        return $course;
    }
}
