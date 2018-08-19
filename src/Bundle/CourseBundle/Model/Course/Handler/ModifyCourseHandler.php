<?php

namespace Sprungbrett\Bundle\CourseBundle\Model\Course\Handler;

use Sprungbrett\Bundle\CourseBundle\Model\Course\Command\ModifyCourseCommand;
use Sprungbrett\Bundle\CourseBundle\Model\Course\CourseInterface;
use Sprungbrett\Bundle\CourseBundle\Model\Course\CourseRepositoryInterface;
use Sprungbrett\Bundle\CourseBundle\Model\Course\Event\CourseModifiedEvent;
use Sprungbrett\Bundle\CourseBundle\Model\Trainer\TrainerRepositoryInterface;
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

    public function __construct(CourseRepositoryInterface $courseRepository, TrainerRepositoryInterface $trainerRepository, EventCollector $eventCollector)
    {
        $this->initializeCourseMapping($trainerRepository);

        $this->courseRepository = $courseRepository;
        $this->eventCollector = $eventCollector;
    }

    public function handle(ModifyCourseCommand $command): CourseInterface
    {
        $course = $this->courseRepository->findById($command->getId(), $command->getLocalization());
        $this->map($course, $command);
        $course->setStructureType($command->getStructureType());
        $course->setContentData($command->getContentData());

        $this->eventCollector->push(
            CourseModifiedEvent::COMPONENT_NAME,
            CourseModifiedEvent::NAME,
            new CourseModifiedEvent($course)
        );

        return $course;
    }
}
