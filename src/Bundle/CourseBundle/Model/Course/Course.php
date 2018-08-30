<?php

namespace Sprungbrett\Bundle\CourseBundle\Model\Course;

use Doctrine\Common\Collections\Collection;
use Sprungbrett\Component\Content\Model\Content;
use Sprungbrett\Component\Content\Model\ContentableInterface;
use Sprungbrett\Component\Translation\Model\Exception\MissingLocalizationException;
use Sprungbrett\Component\Translation\Model\Localization;
use Sprungbrett\Component\Translation\Model\TranslatableTrait;
use Sprungbrett\Component\Translation\Model\TranslationInterface;
use Sulu\Bundle\RouteBundle\Model\RoutableInterface;
use Sulu\Bundle\RouteBundle\Model\RouteInterface;
use Sulu\Component\Persistence\Model\AuditableInterface;
use Sulu\Component\Persistence\Model\AuditableTrait;

class Course implements CourseInterface, AuditableInterface, RoutableInterface
{
    use AuditableTrait;
    use TranslatableTrait;

    /**
     * @var string
     */
    protected $id;

    /**
     * @var int|null
     */
    protected $trainerId;

    /**
     * @var RouteInterface|null
     */
    protected $route;

    public function __construct(string $id, ?Collection $translations = null)
    {
        $this->initializeTranslations($translations);

        $this->id = $id;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getTrainerId(): ?int
    {
        return $this->trainerId;
    }

    public function setTrainerId(?int $trainerId): CourseInterface
    {
        $this->trainerId = $trainerId;

        return $this;
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

    public function setStructureType(string $structureType, ?Localization $localization = null): ContentableInterface
    {
        $this->getTranslation($localization)->setStructureType($structureType);

        return $this;
    }

    public function getContentData(?Localization $localization = null): array
    {
        return $this->getTranslation($localization)->getContentData();
    }

    public function setContentData(array $contentData, ?Localization $localization = null): ContentableInterface
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

    /**
     * @return Collection|CourseTranslationInterface[]
     */
    public function getTranslations(): Collection
    {
        return $this->translations;
    }

    public function getRoute(): ?RouteInterface
    {
        return $this->route;
    }

    public function setRoute(RouteInterface $route): self
    {
        $this->route = $route;

        return $this;
    }

    public function removeRoute(): CourseInterface
    {
        $this->route = null;

        return $this;
    }

    public function getRoutePath(): ?string
    {
        if (!$this->route) {
            return null;
        }

        return $this->route->getPath();
    }

    protected function createTranslation(Localization $localization): TranslationInterface
    {
        return new CourseTranslation($this, $localization, new Content());
    }
}
