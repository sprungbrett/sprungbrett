<?php

namespace Sprungbrett\Bundle\CourseBundle\Model\Course\Handler;

use Sprungbrett\Bundle\CourseBundle\Model\Course\Command\UnpublishCourseCommand;
use Sprungbrett\Bundle\CourseBundle\Model\Course\CourseInterface;
use Sprungbrett\Bundle\CourseBundle\Model\Course\CourseRepositoryInterface;
use Sprungbrett\Bundle\CourseBundle\Model\Course\Event\CourseUnpublishedEvent;
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
