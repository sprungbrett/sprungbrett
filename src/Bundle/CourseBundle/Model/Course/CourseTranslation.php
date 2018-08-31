<?php

namespace Sprungbrett\Bundle\CourseBundle\Model\Course;

use Sprungbrett\Component\Content\Model\ContentableTrait;
use Sprungbrett\Component\Content\Model\ContentInterface;
use Sprungbrett\Component\Translation\Model\Localization;
use Sprungbrett\Component\Translation\Model\TranslationTrait;
use Sulu\Component\Persistence\Model\AuditableInterface;
use Sulu\Component\Persistence\Model\AuditableTrait;

class CourseTranslation implements CourseTranslationInterface, AuditableInterface
{
    use AuditableTrait;
    use ContentableTrait;
    use TranslationTrait;

    /**
     * @var CourseInterface
     */
    protected $course;

    /**
     * @var string
     */
    protected $name;

    /**
     * @var string
     */
    protected $description;

    /**
     * @var string
     */
    protected $workflowStage = CourseInterface::WORKFLOW_STAGE_NEW;

    public function __construct(CourseInterface $course, Localization $localization, ContentInterface $content)
    {
        $this->initializeTranslation($localization);
        $this->initializeContent($content);

        $this->course = $course;
    }

    public function getCourse(): CourseInterface
    {
        return $this->course;
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

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): CourseTranslationInterface
    {
        $this->name = $name;

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
