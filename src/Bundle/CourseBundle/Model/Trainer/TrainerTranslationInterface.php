<?php

namespace Sprungbrett\Bundle\CourseBundle\Model\Trainer;

use Sprungbrett\Component\Translation\Model\Localization;
use Sprungbrett\Component\Translation\Model\TranslationInterface;

interface TrainerTranslationInterface extends TranslationInterface
{
    public function __construct(TrainerInterface $trainer, Localization $localization);

    public function getDescription(): string;

    public function setDescription(string $description): self;
}
