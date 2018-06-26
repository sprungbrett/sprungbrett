<?php

namespace Sprungbrett\Component\Course\Model\Handler;

use Sprungbrett\Component\Course\Model\Command\UnpublishCourseCommand;
use Sprungbrett\Component\Course\Model\CourseInterface;
use Sprungbrett\Component\Course\Model\CourseRepositoryInterface;
use Sprungbrett\Component\Course\Model\Event\CourseUnpublishedEvent;
use Sprungbrett\Component\EventCollector\EventCollector;
use Symfony\Component\Workflow\Workflow;

class UnpublishCourseHandler
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

    public function handle(UnpublishCourseCommand $command): CourseInterface
    {
        $course = $this->courseRepository->findById($command->getId(), $command->getLocalization());

        $this->transition(CourseInterface::TRANSITION_UNPUBLISH, new CourseUnpublishedEvent($course), $course);

        return $course;
    }
}
