<?php

declare(strict_types=1);

namespace Sprungbrett\Bundle\CourseBundle\Model;

interface CourseTranslationInterface
{
    public function __construct(CourseInterface $course, string $locale);

    public function getName(): ?string;

    public function setName(?string $name): self;
}
