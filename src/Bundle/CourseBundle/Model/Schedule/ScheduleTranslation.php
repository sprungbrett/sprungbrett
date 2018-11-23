<?php

declare(strict_types=1);

namespace Sprungbrett\Bundle\CourseBundle\Model\Schedule;

use Sprungbrett\Component\Translatable\Model\TranslationInterface;
use Sprungbrett\Component\Translatable\Model\TranslationTrait;

class ScheduleTranslation implements TranslationInterface, ScheduleTranslationInterface
{
    use TranslationTrait{
        __construct as protected initializeLocale;
    }

    /**
     * @var int
     */
    protected $id;

    /**
     * @var ScheduleInterface
     */
    protected $schedule;

    /**
     * @var string|null
     */
    protected $name;

    /**
     * @var string|null
     */
    protected $description;

    public function __construct(ScheduleInterface $schedule, string $locale)
    {
        $this->schedule = $schedule;

        $this->initializeLocale($locale);
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): ScheduleTranslationInterface
    {
        $this->name = $name;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): ScheduleTranslationInterface
    {
        $this->description = $description;

        return $this;
    }
}
