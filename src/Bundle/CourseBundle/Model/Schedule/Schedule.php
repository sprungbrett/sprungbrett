<?php

declare(strict_types=1);

namespace Sprungbrett\Bundle\CourseBundle\Model\Schedule;

use Sprungbrett\Bundle\CourseBundle\Model\Course\CourseInterface;
use Sprungbrett\Component\Translatable\Model\TranslatableTrait;
use Sprungbrett\Component\Translatable\Model\TranslationInterface;

class Schedule implements ScheduleInterface
{
    use TranslatableTrait {
        __construct as protected initializeTranslations;
    }

    /**
     * @var int
     */
    protected $id;

    /**
     * @var string
     */
    protected $uuid;

    /**
     * @var CourseInterface
     */
    protected $course;

    /**
     * @var string
     */
    protected $stage;

    /**
     * @var int
     */
    protected $maximumParticipants = 0;

    /**
     * @var int
     */
    protected $minimumParticipants = 0;

    /**
     * @var float
     */
    protected $price = 0.0;

    public function __construct(string $uuid, CourseInterface $course, string $stage, ?array $translations = null)
    {
        $this->uuid = $uuid;
        $this->course = $course;
        $this->stage = $stage;

        $this->initializeTranslations($translations);
    }

    public function getUuid(): string
    {
        return $this->uuid;
    }

    public function getCourse(): CourseInterface
    {
        return $this->course;
    }

    public function getCourseId(): string
    {
        return $this->course->getUuid();
    }

    public function getStage(): string
    {
        return $this->stage;
    }

    public function getMaximumParticipants(): int
    {
        return $this->maximumParticipants;
    }

    public function setMaximumParticipants(int $maximumParticipants): ScheduleInterface
    {
        $this->maximumParticipants = $maximumParticipants;

        return $this;
    }

    public function getMinimumParticipants(): int
    {
        return $this->minimumParticipants;
    }

    public function setMinimumParticipants(int $minimumParticipants): ScheduleInterface
    {
        $this->minimumParticipants = $minimumParticipants;

        return $this;
    }

    public function getPrice(): float
    {
        return $this->price;
    }

    public function setPrice(float $price): ScheduleInterface
    {
        $this->price = $price;

        return $this;
    }

    public function getName(?string $locale = null): ?string
    {
        return $this->getTranslation($locale)->getName();
    }

    public function setName(string $description, ?string $locale = null): ScheduleInterface
    {
        $this->getTranslation($locale)->setName($description);

        return $this;
    }

    public function getDescription(?string $locale = null): ?string
    {
        return $this->getTranslation($locale)->getDescription();
    }

    public function setDescription(string $description, ?string $locale = null): ScheduleInterface
    {
        $this->getTranslation($locale)->setDescription($description);

        return $this;
    }

    public function getTranslation(?string $locale = null): ScheduleTranslationInterface
    {
        /** @var ScheduleTranslationInterface $translation */
        $translation = $this->doGetTranslation($locale);

        return $translation;
    }

    protected function createTranslation(string $locale): TranslationInterface
    {
        return new ScheduleTranslation($this, $locale);
    }
}
