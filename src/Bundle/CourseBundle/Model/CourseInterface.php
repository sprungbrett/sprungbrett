<?php

namespace Sprungbrett\Bundle\CourseBundle\Model;

interface CourseInterface
{
    public function __construct(string $uuid, ?array $translations = null);

    public function getUuid(): string;

    public function getName(?string $locale = null): ?string;

    public function setName(string $name, ?string $locale = null): self;

    public function setCurrentLocale(string $currentLocale): void;

    public function getTranslation(?string $locale = null): CourseTranslationInterface;
}