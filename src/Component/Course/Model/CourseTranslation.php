<?php

namespace Sprungbrett\Component\Course\Model;

use Sprungbrett\Component\Translation\Model\Localization;
use Sprungbrett\Component\Translation\Model\TranslationTrait;
use Sprungbrett\Component\Uuid\Model\Uuid;
use Sprungbrett\Component\Uuid\Model\UuidTrait;

class CourseTranslation implements CourseTranslationInterface
{
    use TranslationTrait;
    use UuidTrait;

    /**
     * @var string
     */
    protected $title;

    /**
     * @var string
     */
    protected $description;

    /**
     * @var string
     */
    protected $workflowStage = CourseInterface::WORKFLOW_STAGE_NEW;

    public function __construct(Localization $localization, ?Uuid $uuid = null)
    {
        $this->initializeTranslation($localization);
        $this->initializeUuid($uuid);
    }

    public function getWorkflowStage(): string
    {
        return $this->workflowStage;
    }

    public function setWorkflowStage(string $workflowStage): CourseTranslationInterface
    {
        $this->workflowStage = $workflowStage;

        return $this;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): CourseTranslationInterface
    {
        $this->title = $title;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): CourseTranslationInterface
    {
        $this->description = $description;

        return $this;
    }
}
