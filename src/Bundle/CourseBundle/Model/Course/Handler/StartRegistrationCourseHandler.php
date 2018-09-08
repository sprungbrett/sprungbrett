<?php

namespace Sprungbrett\Bundle\CourseBundle\Model\Course\Handler;

use Sprungbrett\Bundle\CourseBundle\Model\Course\Command\PublishCourseCommand;
use Sprungbrett\Bundle\CourseBundle\Model\Course\CourseInterface;
use Sprungbrett\Bundle\CourseBundle\Model\Course\CourseRepositoryInterface;
use Sprungbrett\Bundle\CourseBundle\Model\Course\Event\CoursePublishedEvent;
use Sprungbrett\Bundle\CourseBundle\Model\Course\Event\CourseStartedRegistrationEvent;
use Sprungbrett\Component\EventCollector\EventCollector;
use Symfony\Component\Workflow\Workflow;

class StartRegistrationCourseHandler
{
    use CourseTransitTrait;

    /**
     * @var CourseRepositoryInterface
     */
    private $courseRepository;

    public function __construct(
        CourseRepositoryInterface $courseRepository,
        Workflow $workflow,
        EventCollector $eventCollector
    ) {
        $this->initializeTransit($workflow, $eventCollector);

        $this->courseRepository = $courseRepository;
    }

    public function handle(PublishCourseCommand $command): CourseInterface
    {
        $course = $this->courseRepository->findById($command->getId());

        $this->transition(CourseInterface::PHASE_TRANSITION_START_REGISTRATION, new CourseStartedRegistrationEvent($course), $course);

        return $course;
    }
}
