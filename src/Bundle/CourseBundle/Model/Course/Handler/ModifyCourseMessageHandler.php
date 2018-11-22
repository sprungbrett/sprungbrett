<?php

declare(strict_types=1);

namespace Sprungbrett\Bundle\CourseBundle\Model\Course\Handler;

use Sprungbrett\Bundle\ContentBundle\Stages;
use Sprungbrett\Bundle\CourseBundle\Model\Course\CourseRepositoryInterface;
use Sprungbrett\Bundle\CourseBundle\Model\Course\Event\CourseModifiedEvent;
use Sprungbrett\Bundle\CourseBundle\Model\Course\Exception\CourseNotFoundException;
use Sprungbrett\Bundle\CourseBundle\Model\Course\Message\ModifyCourseMessage;
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
        $course = $this->courseRepository->findById($message->getUuid(), Stages::DRAFT, $message->getLocale());
        if (!$course) {
            throw new CourseNotFoundException($message->getUuid());
        }

        $course->setName($message->getName());

        $this->messageCollector->push(new CourseModifiedEvent($course));
    }
}
