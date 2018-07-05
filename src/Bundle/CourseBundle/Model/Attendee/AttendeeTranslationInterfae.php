<?php

namespace Sprungbrett\Bundle\CourseBundle\Model\Attendee;

use Sprungbrett\Component\Translation\Model\Localization;
use Sprungbrett\Component\Translation\Model\TranslationInterface;

interface AttendeeTranslationInterfae extends TranslationInterface
{
    public function __construct(AttendeeInterface $attendee, Localization $localization);

    public function getDescription(): string;

    public function setDescription(string $description): self;
}
