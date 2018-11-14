<?php

namespace Sprungbrett\Bundle\CourseBundle\Model\Handler;

use Sprungbrett\Bundle\CourseBundle\Model\CourseRepositoryInterface;
use Sprungbrett\Bundle\CourseBundle\Model\Event\CourseRemovedEvent;
use Sprungbrett\Bundle\CourseBundle\Model\Exception\CourseNotFoundException;
use Sprungbrett\Bundle\CourseBundle\Model\Message\RemoveCourseMessage;
use Sprungbrett\Component\MessageCollector\MessageCollector;

class RemoveCourseMessageHandler
{
    /**
     * @var MessageCollector
     */
    private $messageCollector;

    /**
     * @var CourseRepositoryInterface
     */
    private $courseRepository;

    public function __construct(MessageCollector $messageCollector, CourseRepositoryInterface $courseRepository)
    {
        $this->messageCollector = $messageCollector;
        $this->courseRepository = $courseRepository;
    }

    public function __invoke(RemoveCourseMessage $message): void
    {
        $course = $this->courseRepository->findById($message->getUuid());
        if (!$course) {
            throw new CourseNotFoundException($message->getUuid());
        }

        $this->courseRepository->remove($course);

        $this->messageCollector->push(new CourseRemovedEvent($course->getUuid()));
    }
}
