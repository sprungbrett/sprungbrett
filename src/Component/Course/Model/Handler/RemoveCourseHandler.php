<?php

namespace Sprungbrett\Component\Course\Model\Handler;

use Sprungbrett\Component\Course\Model\Command\RemoveCourseCommand;
use Sprungbrett\Component\Course\Model\CourseInterface;
use Sprungbrett\Component\Course\Model\CourseRepositoryInterface;
use Sprungbrett\Component\Course\Model\Event\CourseRemovedEvent;
use Sprungbrett\Component\EventCollector\EventCollector;

class RemoveCourseHandler
{
    const COMPONENT_NAME = CourseRemovedEvent::COMPONENT_NAME;
    const EVENT_NAME = CourseRemovedEvent::NAME;

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
        $course = $this->courseRepository->findByUuid($command->getUuid());
        $this->courseRepository->remove($course);

        $this->eventCollector->push(self::COMPONENT_NAME, self::EVENT_NAME, new CourseRemovedEvent($course));

        return $course;
    }
}
