<?php

declare(strict_types=1);

namespace Sprungbrett\Bundle\CourseBundle\Model;

use Sprungbrett\Component\Translatable\Model\TranslatableInterface;

interface CourseInterface extends TranslatableInterface
{
    public function __construct(string $uuid, string $stage, ?array $translations = null);

    public function getUuid(): string;

    public function getStage(): string;

    public function getName(?string $locale = null): ?string;

    public function setName(?string $name, ?string $locale = null): self;

    public function getTranslation(?string $locale = null): CourseTranslationInterface;
}
