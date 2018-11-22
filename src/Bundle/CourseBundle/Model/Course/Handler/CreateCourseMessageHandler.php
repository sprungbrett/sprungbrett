<?php

declare(strict_types=1);

namespace Sprungbrett\Bundle\CourseBundle\Model\Course\Handler;

use Sprungbrett\Bundle\ContentBundle\Model\Content\Message\CreateContentMessage;
use Sprungbrett\Bundle\ContentBundle\Stages;
use Sprungbrett\Bundle\CourseBundle\Model\Course\CourseRepositoryInterface;
use Sprungbrett\Bundle\CourseBundle\Model\Course\Event\CourseCreatedEvent;
use Sprungbrett\Bundle\CourseBundle\Model\Course\Message\CreateCourseMessage;
use Sprungbrett\Component\MessageCollector\MessageCollector;
use Symfony\Component\Messenger\MessageBusInterface;

class CreateCourseMessageHandler
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

    /**
     * @var string
     */
    private $defaultType;

    public function __construct(
        MessageBusInterface $messageBus,
        MessageCollector $messageCollector,
        CourseRepositoryInterface $courseRepository,
        string $defaultType = 'default'
    ) {
        $this->messageBus = $messageBus;
        $this->messageCollector = $messageCollector;
        $this->courseRepository = $courseRepository;
        $this->defaultType = $defaultType;
    }

    public function __invoke(CreateCourseMessage $message): void
    {
        $course = $this->courseRepository->create($message->getUuid(), Stages::DRAFT, $message->getLocale());
        $course->setName($message->getName());

        $this->messageBus->dispatch(
            new CreateContentMessage(
                'courses',
                $message->getUuid(),
                $message->getLocale(),
                ['type' => $this->defaultType, 'data' => []]
            )
        );

        $this->messageCollector->push(new CourseCreatedEvent($course));
    }
}
