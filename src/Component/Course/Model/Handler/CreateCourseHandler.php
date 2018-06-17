<?php

namespace Sprungbrett\Component\Course\Model\Handler;

use Sprungbrett\Component\Course\Model\Command\CreateCourseCommand;
use Sprungbrett\Component\Course\Model\CourseInterface;
use Sprungbrett\Component\Course\Model\CourseRepositoryInterface;
use Sprungbrett\Component\Course\Model\Event\CourseCreatedEvent;
use Sprungbrett\Component\EventCollector\EventCollector;

class CreateCourseHandler extends MappingCourseHandler
{
    const COMPONENT_NAME = CourseCreatedEvent::COMPONENT_NAME;
    const EVENT_NAME = CourseCreatedEvent::NAME;

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

    public function handle(CreateCourseCommand $command): CourseInterface
    {
        $course = $this->courseRepository->create($command->getLocalization());
        $this->map($course, $command);

        $this->eventCollector->push(self::COMPONENT_NAME, self::EVENT_NAME, new CourseCreatedEvent($course));

        return $course;
    }
}
