<?php

namespace Sprungbrett\Component\Course\Model\Handler;

use Sprungbrett\Component\Course\Model\Command\ModifyCourseCommand;
use Sprungbrett\Component\Course\Model\CourseInterface;
use Sprungbrett\Component\Course\Model\CourseRepositoryInterface;
use Sprungbrett\Component\Course\Model\Event\CourseModifiedEvent;
use Sprungbrett\Component\EventCollector\EventCollector;

class ModifyCourseHandler
{
    use CourseMappingTrait;

    /**
     * @var CourseRepositoryInterface
     */
    private $courseRepository;

    /**
     * @var EventCollector
     */
    private $eventCollector;

    public function __construct(CourseRepositoryInterface $courseRepository, EventCollector $eventCollector)
    {
        $this->courseRepository = $courseRepository;
        $this->eventCollector = $eventCollector;
    }

    public function handle(ModifyCourseCommand $command): CourseInterface
    {
        $course = $this->courseRepository->findByUuid($command->getUuid(), $command->getLocalization());
        $this->map($course, $command);

        $this->eventCollector->push(
            CourseModifiedEvent::COMPONENT_NAME,
            CourseModifiedEvent::NAME,
            new CourseModifiedEvent($course)
        );

        return $course;
    }
}
