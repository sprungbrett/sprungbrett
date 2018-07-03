<?php

namespace Sprungbrett\Bundle\CourseBundle\Model\Course;

use Sprungbrett\Component\Content\Model\ContentableInterface;
use Sprungbrett\Component\Content\Model\ContentInterface;
use Sprungbrett\Component\Translation\Model\Localization;
use Sprungbrett\Component\Translation\Model\TranslationInterface;

interface CourseTranslationInterface extends TranslationInterface, ContentableInterface
{
    public function __construct(CourseInterface $course, Localization $localization, ContentInterface $content);

    public function getWorkflowStage(): string;

    public function setWorkflowStage(string $workflowStage): self;

    public function getTitle(): ?string;

    public function setTitle(string $title): self;

    public function getDescription(): ?string;

    public function setDescription(string $description): self;
}
