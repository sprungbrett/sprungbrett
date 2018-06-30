<?php

namespace Sprungbrett\Bundle\CourseBundle\Model\Course\Handler;

use Sprungbrett\Bundle\CourseBundle\Model\Course\Command\CreateCourseCommand;
use Sprungbrett\Bundle\CourseBundle\Model\Course\CourseInterface;
use Sprungbrett\Bundle\CourseBundle\Model\Course\CourseRepositoryInterface;
use Sprungbrett\Bundle\CourseBundle\Model\Course\Event\CourseCreatedEvent;
use Sprungbrett\Component\EventCollector\EventCollector;
use Symfony\Component\Workflow\Workflow;

class CreateCourseHandler
{
    use CourseMappingTrait;
    use CourseTransitTrait;

    /**
     * @var CourseRepositoryInterface
     */
    private $courseRepository;

    /**
     * @var string
     */
    private $defaultStructureType;

    public function __construct(
        CourseRepositoryInterface $courseRepository,
        Workflow $workflow,
        EventCollector $eventCollector,
        string $defaultStructureType
    ) {
        $this->initializeTransit($workflow, $eventCollector);

        $this->courseRepository = $courseRepository;
        $this->defaultStructureType = $defaultStructureType;
    }

    public function handle(CreateCourseCommand $command): CourseInterface
    {
        $course = $this->courseRepository->create($command->getLocalization());
        $this->transition(CourseInterface::TRANSITION_CREATE, new CourseCreatedEvent($course), $course);

        $this->map($course, $command);
        $course->setStructureType($this->defaultStructureType);

        return $course;
    }
}
