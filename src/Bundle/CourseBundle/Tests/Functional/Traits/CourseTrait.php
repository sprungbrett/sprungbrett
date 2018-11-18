<?php

declare(strict_types=1);

namespace Sprungbrett\Bundle\CourseBundle\Tests\Functional\Traits;

use Sprungbrett\Bundle\ContentBundle\Stages;
use Sprungbrett\Bundle\CourseBundle\Model\CourseInterface;
use Sprungbrett\Bundle\CourseBundle\Model\Exception\CourseNotFoundException;
use Sprungbrett\Bundle\CourseBundle\Model\Message\CreateCourseMessage;
use Sprungbrett\Bundle\CourseBundle\Model\Message\PublishCourseMessage;
use Sprungbrett\Bundle\CourseBundle\Model\Query\FindCourseQuery;
use Symfony\Component\Messenger\MessageBusInterface;

trait CourseTrait
{
    public function createCourse(string $name = 'Sprungbrett', string $locale = 'en'): CourseInterface
    {
        $message = new CreateCourseMessage($locale, ['name' => $name]);
        $this->getMessageBus()->dispatch($message);

        $course = $this->findCourse($message->getUuid(), $message->getLocale());
        if (!$course) {
            throw new \RuntimeException();
        }

        return $course;
    }

    public function publishCourse(CourseInterface $course): void
    {
        $this->getMessageBus()->dispatch(
            new PublishCourseMessage($course->getUuid(), $course->getCurrentLocale() ?: 'en')
        );
    }

    public function findCourse(string $uuid, string $locale = 'en', string $stage = Stages::DRAFT): ?CourseInterface
    {
        try {
            return $this->getMessageBus()->dispatch(new FindCourseQuery($uuid, $locale, $stage));
        } catch (CourseNotFoundException $exception) {
            return null;
        }
    }

    abstract public function getMessageBus(): MessageBusInterface;
}
