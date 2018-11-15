<?php

declare(strict_types=1);

namespace Sprungbrett\Bundle\CourseBundle\Model\Handler;

use Sprungbrett\Bundle\ContentBundle\Stages;
use Sprungbrett\Bundle\CourseBundle\Model\CourseRepositoryInterface;
use Sprungbrett\Bundle\CourseBundle\Model\Event\CourseCreatedEvent;
use Sprungbrett\Bundle\CourseBundle\Model\Message\CreateCourseMessage;
use Sprungbrett\Component\MessageCollector\MessageCollector;

class CreateCourseMessageHandler
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

    public function __invoke(CreateCourseMessage $message): void
    {
        $course = $this->courseRepository->create($message->getUuid(), Stages::DRAFT, $message->getLocale());
        $course->setName($message->getName());

        $this->messageCollector->push(new CourseCreatedEvent($course));
    }
}
