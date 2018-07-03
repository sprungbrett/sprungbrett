<?php

namespace Sprungbrett\Bundle\CourseBundle\Model\Trainer;

use Sprungbrett\Component\Translation\Model\Localization;

interface TrainerRepositoryInterface
{
    public function findOrCreateTrainerById(int $id, Localization $localization): TrainerInterface;
}
