<?php

namespace Sprungbrett\Bundle\CourseBundle\Model\Course;

use Sprungbrett\Component\Content\Model\ContentableInterface;
use Sprungbrett\Component\Content\Model\ContentInterface;
use Sprungbrett\Component\Translation\Model\Localization;
use Sprungbrett\Component\Translation\Model\TranslationInterface;

interface CourseTranslationInterface extends TranslationInterface, ContentableInterface
{
    public function __construct(CourseInterface $course, Localization $localization, ContentInterface $content);

    public function getName(): ?string;

    public function setName(string $name): self;

    public function getDescription(): ?string;

    public function setDescription(string $description): self;

    /**
     * FIXME remove from here (not domain logic).
     */
    public function removeRoute(): self;

    /**
     * FIXME remove from here (not domain logic).
     */
    public function getRoutePath(): ?string;
}
