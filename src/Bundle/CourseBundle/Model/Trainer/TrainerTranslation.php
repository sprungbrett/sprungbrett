<?php

namespace Sprungbrett\Bundle\CourseBundle\Model\Trainer;

use Sprungbrett\Component\Translation\Model\Localization;
use Sprungbrett\Component\Translation\Model\TranslationTrait;
use Sulu\Component\Persistence\Model\AuditableInterface;
use Sulu\Component\Persistence\Model\AuditableTrait;

class TrainerTranslation implements TrainerTranslationInterface, AuditableInterface
{
    use AuditableTrait;
    use TranslationTrait;

    /**
     * @var TrainerInterface
     */
    protected $trainer;

    /**
     * @var string
     */
    protected $description = '';

    public function __construct(TrainerInterface $trainer, Localization $localization)
    {
        $this->initializeTranslation($localization);

        $this->trainer = $trainer;
    }

    public function getTrainer(): TrainerInterface
    {
        return $this->trainer;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setDescription(string $description): TrainerTranslationInterface
    {
        $this->description = $description;

        return $this;
    }
}
