<?php

declare(strict_types=1);

namespace Sprungbrett\Bundle\CourseBundle\Model\Course\Handler;

use Sprungbrett\Bundle\CourseBundle\Model\Course\CourseInterface;
use Sprungbrett\Bundle\CourseBundle\Model\Course\CourseRepositoryInterface;
use Sprungbrett\Bundle\CourseBundle\Model\Course\Exception\CourseNotFoundException;
use Sprungbrett\Bundle\CourseBundle\Model\Course\Query\FindCourseQuery;

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
        $course = $this->courseRepository->findById($query->getUuid(), $query->getStage(), $query->getLocale());
        if (!$course) {
            throw new CourseNotFoundException($query->getUuid());
        }

        return $course;
    }
}
