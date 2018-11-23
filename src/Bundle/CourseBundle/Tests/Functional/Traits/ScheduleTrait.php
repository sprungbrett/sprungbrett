<?php

declare(strict_types=1);

namespace Sprungbrett\Bundle\CourseBundle\Tests\Functional\Traits;

use Sprungbrett\Bundle\ContentBundle\Stages;
use Sprungbrett\Bundle\CourseBundle\Model\Course\CourseInterface;
use Sprungbrett\Bundle\CourseBundle\Model\Schedule\Exception\ScheduleNotFoundException;
use Sprungbrett\Bundle\CourseBundle\Model\Schedule\Message\CreateScheduleMessage;
use Sprungbrett\Bundle\CourseBundle\Model\Schedule\Message\PublishScheduleMessage;
use Sprungbrett\Bundle\CourseBundle\Model\Schedule\Query\FindScheduleQuery;
use Sprungbrett\Bundle\CourseBundle\Model\Schedule\ScheduleInterface;
use Symfony\Component\Messenger\MessageBusInterface;

trait ScheduleTrait
{
    public function createSchedule(
        CourseInterface $course,
        string $name = 'Sprungbrett',
        string $description = 'Sprungbrett is awesome',
        string $locale = 'en'
    ): ScheduleInterface {
        $message = new CreateScheduleMessage(
            $locale,
            [
                'name' => $name,
                'description' => $description,
                'course' => $course->getUuid(),
                'minimumParticipants' => 5,
                'maximumParticipants' => 15,
                'price' => 15.5,
            ]
        );
        $this->getMessageBus()->dispatch($message);

        $schedule = $this->findSchedule($message->getUuid(), $message->getLocale());
        if (!$schedule) {
            throw new \RuntimeException();
        }

        return $schedule;
    }

    public function publishSchedule(CourseInterface $course): void
    {
        $this->getMessageBus()->dispatch(
            new PublishScheduleMessage($course->getUuid(), $course->getCurrentLocale() ?: 'en')
        );
    }

    public function findSchedule(string $uuid, string $locale = 'en', string $stage = Stages::DRAFT): ?ScheduleInterface
    {
        try {
            return $this->getMessageBus()->dispatch(new FindScheduleQuery($uuid, $locale, $stage));
        } catch (ScheduleNotFoundException $exception) {
            return null;
        }
    }

    abstract public function getMessageBus(): MessageBusInterface;
}
