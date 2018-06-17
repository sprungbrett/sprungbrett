<?php

namespace Sprungbrett\Component\Course\Model\Handler;

use Sprungbrett\Component\Course\Model\Command\CreateCourseCommand;
use Sprungbrett\Component\Course\Model\CourseInterface;
use Sprungbrett\Component\Course\Model\CourseRepositoryInterface;

class CreateCourseHandler
{
    /**
     * @var CourseRepositoryInterface
     */
    private $courseRepository;

    public function __construct(CourseRepositoryInterface $courseRepository)
    {
        $this->courseRepository = $courseRepository;
    }

    public function handle(CreateCourseCommand $command): CourseInterface
    {
        $course = $this->courseRepository->create($command->getLocalization());

        $course->setTitle($command->getTitle());

        return $course;
    }
}
