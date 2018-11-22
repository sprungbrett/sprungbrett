<?php

declare(strict_types=1);

namespace Sprungbrett\Bundle\CourseBundle\Model\Course\Handler;

use Sprungbrett\Bundle\ContentBundle\Model\Content\Message\PublishContentMessage;
use Sprungbrett\Bundle\ContentBundle\Stages;
use Sprungbrett\Bundle\CourseBundle\Model\Course\CourseRepositoryInterface;
use Sprungbrett\Bundle\CourseBundle\Model\Course\Event\CoursePublishedEvent;
use Sprungbrett\Bundle\CourseBundle\Model\Course\Exception\CourseNotFoundException;
use Sprungbrett\Bundle\CourseBundle\Model\Course\Message\PublishCourseMessage;
use Sprungbrett\Component\MessageCollector\MessageCollector;
use Symfony\Component\Messenger\MessageBusInterface;

class PublishCourseMessageHandler
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

    public function __invoke(PublishCourseMessage $message): void
    {
        $draftCourse = $this->courseRepository->findById($message->getUuid(), Stages::DRAFT, $message->getLocale());
        if (!$draftCourse) {
            throw new CourseNotFoundException($message->getUuid());
        }

        $liveCourse = $this->courseRepository->findById($message->getUuid(), Stages::LIVE, $message->getLocale());
        if (!$liveCourse) {
            $liveCourse = $this->courseRepository->create($message->getUuid(), Stages::LIVE, $message->getLocale());
        }

        $liveCourse->setName($draftCourse->getName());

        $this->messageBus->dispatch(new PublishContentMessage('courses', $message->getUuid(), $message->getLocale()));

        $this->messageCollector->push(new CoursePublishedEvent($message->getLocale(), $liveCourse));
    }
}
