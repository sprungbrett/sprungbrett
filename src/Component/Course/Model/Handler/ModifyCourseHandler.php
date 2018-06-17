<?php

namespace Sprungbrett\Component\Course\Model\Handler;

use Sprungbrett\Component\Course\Model\Command\ModifyCourseCommand;
use Sprungbrett\Component\Course\Model\CourseInterface;
use Sprungbrett\Component\Course\Model\CourseRepositoryInterface;

class ModifyCourseHandler
{
    /**
     * @var CourseRepositoryInterface
     */
    private $courseRepository;

    public function __construct(CourseRepositoryInterface $courseRepository)
    {
        $this->courseRepository = $courseRepository;
    }

    public function handle(ModifyCourseCommand $command): CourseInterface
    {
        $course = $this->courseRepository->findByUuid($command->getUuid(), $command->getLocalization());
        $course->setTitle($command->getTitle());

        return $course;
    }
}
