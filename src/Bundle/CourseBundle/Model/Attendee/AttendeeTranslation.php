<?php

namespace Sprungbrett\Bundle\CourseBundle\Model\Attendee;

use Sprungbrett\Component\Translation\Model\Localization;
use Sprungbrett\Component\Translation\Model\TranslationTrait;
use Sulu\Component\Persistence\Model\AuditableInterface;
use Sulu\Component\Persistence\Model\AuditableTrait;

class AttendeeTranslation implements AttendeeTranslationInterfae, AuditableInterface
{
    use AuditableTrait;
    use TranslationTrait;

    /**
     * @var AttendeeInterface
     */
    protected $attendee;

    /**
     * @var string
     */
    protected $description = '';

    public function __construct(AttendeeInterface $attendee, Localization $localization)
    {
        $this->initializeTranslation($localization);

        $this->attendee = $attendee;
    }

    public function getAttendee(): AttendeeInterface
    {
        return $this->attendee;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setDescription(string $description): AttendeeTranslationInterfae
    {
        $this->description = $description;

        return $this;
    }
}
