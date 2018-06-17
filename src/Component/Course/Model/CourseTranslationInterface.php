<?php

namespace Sprungbrett\Component\Course\Model;

use Sprungbrett\Component\Translation\Model\TranslationInterface;

interface CourseTranslationInterface extends TranslationInterface
{
    public function getTitle(): ?string;

    public function setTitle(string $title): CourseTranslation;
}