<?php

namespace Sprungbrett\Component\Course\Model;

use Doctrine\Common\Collections\Collection;
use Sprungbrett\Component\Content\Model\Content;
use Sprungbrett\Component\Translation\Model\Exception\MissingLocalizationException;
use Sprungbrett\Component\Translation\Model\Localization;
use Sprungbrett\Component\Translation\Model\TranslatableTrait;
use Sprungbrett\Component\Translation\Model\TranslationInterface;

class Course implements CourseInterface
{
    use TranslatableTrait;

    /**
     * @var string
     */
    protected $id;

    public function __construct(string $id, ?Collection $translations = null)
    {
        $this->initializeTranslations($translations);

        $this->id = $id;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getWorkflowStage(?Localization $localization = null): string
    {
        return $this->getTranslation($localization)->getWorkflowStage();
    }

    public function setWorkflowStage(string $workflowStage, ?Localization $localization = null): CourseInterface
    {
        $this->getTranslation($localization)->setWorkflowStage($workflowStage);

        return $this;
    }

    public function getTitle(?Localization $localization = null): ?string
    {
        return $this->getTranslation($localization)->getTitle();
    }

    public function setTitle(string $title, ?Localization $localization = null): CourseInterface
    {
        $this->getTranslation($localization)->setTitle($title);

        return $this;
    }

    public function getDescription(?Localization $localization = null): ?string
    {
        return $this->getTranslation($localization)->getDescription();
    }

    public function setDescription(string $description, ?Localization $localization = null): CourseInterface
    {
        $this->getTranslation($localization)->setDescription($description);

        return $this;
    }

    public function getStructureType(?Localization $localization = null): string
    {
        return $this->getTranslation($localization)->getStructureType();
    }

    public function setStructureType(string $structureType, ?Localization $localization = null): CourseInterface
    {
        $this->getTranslation($localization)->setStructureType($structureType);

        return $this;
    }

    public function getContentData(?Localization $localization = null): array
    {
        return $this->getTranslation($localization)->getContentData();
    }

    public function setContentData(array $contentData, ?Localization $localization = null): CourseInterface
    {
        $this->getTranslation($localization)->setContentData($contentData);

        return $this;
    }

    /**
     * @throws MissingLocalizationException
     */
    public function getTranslation(?Localization $localization = null): CourseTranslationInterface
    {
        /** @var CourseTranslationInterface $translation */
        $translation = $this->doGetTranslation($localization);

        return $translation;
    }

    protected function createTranslation(Localization $localization): TranslationInterface
    {
        return new CourseTranslation($this->id, $localization, new Content());
    }
}
