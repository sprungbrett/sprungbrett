<?php

namespace Sprungbrett\Component\Course\Model\Handler;

use Sprungbrett\Component\Course\Model\Command\ModifyCourseCommand;
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

    public function handle(ModifyCourseCommand $command)
    {
        $course = $this->courseRepository->findByUuid($command->getUuid());
        $course->setTitle($command->getTitle());

        return $course;
    }
}
