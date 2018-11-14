<?php

namespace Sprungbrett\Bundle\CourseBundle\Model\Handler;

use Sprungbrett\Bundle\CourseBundle\Model\CourseRepositoryInterface;
use Sprungbrett\Bundle\CourseBundle\Model\Event\CourseModifiedEvent;
use Sprungbrett\Bundle\CourseBundle\Model\Exception\CourseNotFoundException;
use Sprungbrett\Bundle\CourseBundle\Model\Message\ModifyCourseMessage;
use Sprungbrett\Component\MessageCollector\MessageCollector;

class ModifyCourseMessageHandler
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

    public function __invoke(ModifyCourseMessage $message): void
    {
        $course = $this->courseRepository->findById($message->getUuid(), $message->getLocale());
        if (!$course) {
            throw new CourseNotFoundException($message->getUuid());
        }

        $course->setName($message->getName());

        $this->messageCollector->push(new CourseModifiedEvent());
    }
}
