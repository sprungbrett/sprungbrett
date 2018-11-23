<?php

declare(strict_types=1);

namespace Sprungbrett\Bundle\CourseBundle\Model\Schedule;

use Sprungbrett\Component\Translatable\Model\TranslationInterface;

interface ScheduleTranslationInterface extends TranslationInterface
{
    public function __construct(ScheduleInterface $schedule, string $locale);

    public function getName(): ?string;

    public function setName(?string $name): ScheduleTranslationInterface;

    public function getDescription(): ?string;

    public function setDescription(string $description): self;
}
