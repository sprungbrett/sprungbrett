<?php

declare(strict_types=1);

namespace Sprungbrett\Bundle\PortalBundle\Model\CourseView;

use Sprungbrett\Bundle\ContentBundle\Model\Content\ContentInterface;
use Sprungbrett\Bundle\CourseBundle\Model\Course\CourseInterface;
use Sprungbrett\Bundle\CourseBundle\Model\Schedule\ScheduleInterface;
use Sulu\Bundle\RouteBundle\Model\RoutableInterface;

interface CourseViewInterface extends RoutableInterface
{
    public function __construct(string $uuid, string $locale);

    public function getUuid(): string;

    public function getLocale(): string;

    public function getCourse(): CourseInterface;

    public function setCourse(CourseInterface $course): self;

    public function getContent(): ContentInterface;

    public function setContent(ContentInterface $content): self;

    /**
     * @return ScheduleInterface[]
     */
    public function getSchedules(): array;

    public function addSchedule(ScheduleInterface $schedule): self;

    public function removeSchedule(ScheduleInterface $schedule): self;
}
