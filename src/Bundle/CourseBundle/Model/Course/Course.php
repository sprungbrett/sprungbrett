<?php

namespace Sprungbrett\Bundle\CourseBundle\Model\Course;

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
     * @var string
     */
    protected $workflowStage = CourseInterface::WORKFLOW_STAGE_NEW;

    public function __construct(string $id, ?array $translations = null)
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

    public function getWorkflowStage(): string
    {
        return $this->workflowStage;
    }

    public function setWorkflowStage(string $workflowStage): CourseInterface
    {
        $this->workflowStage = $workflowStage;

        return $this;
    }

    public function getName(?Localization $localization = null): ?string
    {
        return $this->getTranslation($localization)->getName();
    }

    public function setName(string $name, ?Localization $localization = null): CourseInterface
    {
        $this->getTranslation($localization)->setName($name);

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
     * @return CourseTranslationInterface[]
     */
    public function getTranslations(): array
    {
        return $this->translations->getValues();
    }

    public function getRoute(): ?RouteInterface
    {
        $translation = $this->getTranslation();
        if (!$translation instanceof RoutableInterface) {
            return null;
        }

        return $translation->getRoute();
    }

    public function setRoute(RouteInterface $route): self
    {
        $translation = $this->getTranslation();
        if (!$translation instanceof RoutableInterface) {
            return $this;
        }

        $translation->setRoute($route);

        return $this;
    }

    public function removeRoute(): CourseInterface
    {
        $this->getTranslation()->removeRoute();

        return $this;
    }

    public function getRoutePath(): ?string
    {
        return $this->getTranslation()->getRoutePath();
    }

    protected function createTranslation(Localization $localization): TranslationInterface
    {
        return new CourseTranslation($this, $localization, new Content());
    }
}
