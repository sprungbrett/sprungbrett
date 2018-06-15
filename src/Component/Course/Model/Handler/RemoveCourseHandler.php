<?php

namespace Sprungbrett\Component\Course\Model\Handler;

use Sprungbrett\Component\Course\Model\Command\RemoveCourseCommand;
use Sprungbrett\Component\Course\Model\CourseInterface;
use Sprungbrett\Component\Course\Model\CourseRepositoryInterface;

class RemoveCourseHandler
{
    /**
     * @var CourseRepositoryInterface
     */
    private $courseRepository;

    public function __construct(CourseRepositoryInterface $courseRepository)
    {
        $this->courseRepository = $courseRepository;
    }

    public function handle(RemoveCourseCommand $command): CourseInterface
    {
        $course = $this->courseRepository->findByUuid($command->getUuid());
        $this->courseRepository->remove($course);

        return $course;
    }
}
