<?php

declare(strict_types=1);

namespace Sprungbrett\Bundle\CourseBundle\Model\Handler;

use Sprungbrett\Bundle\ContentBundle\Model\Content\Message\RemoveContentMessage;
use Sprungbrett\Bundle\CourseBundle\Model\CourseRepositoryInterface;
use Sprungbrett\Bundle\CourseBundle\Model\Event\CourseRemovedEvent;
use Sprungbrett\Bundle\CourseBundle\Model\Message\RemoveCourseMessage;
use Sprungbrett\Component\MessageCollector\MessageCollector;
use Symfony\Component\Messenger\MessageBusInterface;

class RemoveCourseMessageHandler
{
    /**
     * @var MessageBusInterface
     */
    private $messageBus;

    /**
     * @var MessageCollector
     */
    private $messageCollector;

    /**
     * @var CourseRepositoryInterface
     */
    private $courseRepository;

    public function __construct(
        MessageBusInterface $messageBus,
        MessageCollector $messageCollector,
        CourseRepositoryInterface $courseRepository
    ) {
        $this->messageBus = $messageBus;
        $this->messageCollector = $messageCollector;
        $this->courseRepository = $courseRepository;
    }

    public function __invoke(RemoveCourseMessage $message): void
    {
        $courses = $this->courseRepository->findAllById($message->getUuid());
        foreach ($courses as $course) {
            $this->courseRepository->remove($course);
        }

        $this->messageBus->dispatch(new RemoveContentMessage('courses', $message->getUuid()));

        $this->messageCollector->push(new CourseRemovedEvent($message->getUuid()));
    }
}
