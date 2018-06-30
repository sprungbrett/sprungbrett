<?php

namespace Sprungbrett\Bundle\CourseBundle\Model\Course\Handler;

use Sprungbrett\Bundle\CourseBundle\Model\Course\Command\RemoveCourseCommand;
use Sprungbrett\Bundle\CourseBundle\Model\Course\CourseInterface;
use Sprungbrett\Bundle\CourseBundle\Model\Course\CourseRepositoryInterface;
use Sprungbrett\Bundle\CourseBundle\Model\Course\Event\CourseRemovedEvent;
use Sprungbrett\Component\EventCollector\EventCollector;

class RemoveCourseHandler
{
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

    public function handle(RemoveCourseCommand $command): CourseInterface
    {
        $course = $this->courseRepository->findById($command->getId());
        $this->courseRepository->remove($course);

        $this->eventCollector->push(CourseRemovedEvent::COMPONENT_NAME, CourseRemovedEvent::NAME, new CourseRemovedEvent($course));

        return $course;
    }
}
