<?php

declare(strict_types=1);

namespace Sprungbrett\Bundle\CourseBundle\Model\Schedule;

use Sprungbrett\Bundle\CourseBundle\Model\Course\CourseInterface;
use Sprungbrett\Component\Translatable\Model\TranslatableInterface;

interface ScheduleInterface extends TranslatableInterface
{
    public function __construct(string $uuid, CourseInterface $course, string $stage);

    public function getUuid(): string;

    public function getCourse(): CourseInterface;

    public function getStage(): string;

    public function getMaximumParticipants(): int;

    public function setMaximumParticipants(int $maximumParticipants): self;

    public function getMinimumParticipants(): int;

    public function setMinimumParticipants(int $minimumParticipants): self;

    public function getPrice(): float;

    public function setPrice(float $price): ScheduleInterface;

    public function getName(?string $locale = null): ?string;

    public function setName(string $description, ?string $locale = null): self;

    public function getDescription(?string $locale = null): ?string;

    public function setDescription(string $description, ?string $locale = null): self;

    public function getTranslation(?string $locale = null): ScheduleTranslationInterface;
}
