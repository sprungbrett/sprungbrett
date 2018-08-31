<?php

namespace Sprungbrett\Bundle\CourseBundle\Structure;

use Sprungbrett\Bundle\CourseBundle\Model\Course\CourseInterface;
use Sprungbrett\Component\Translation\Model\TranslationInterface;
use Sulu\Component\Content\Compat\Structure\StructureBridge;
use Sulu\Component\Content\Document\RedirectType;
use Sulu\Component\Content\Document\WorkflowStage;

class CourseBridge extends StructureBridge
{
    /**
     * @var CourseInterface
     */
    private $course;

    /**
     * @var string
     */
    private $webspaceKey;

    public function getCourse(): CourseInterface
    {
        return $this->course;
    }

    public function setCourse(CourseInterface $course): self
    {
        $this->course = $course;

        return $this;
    }

    public function getWebspaceKey(): string
    {
        return $this->webspaceKey;
    }

    public function setWebspaceKey($webspaceKey): self
    {
        $this->webspaceKey = $webspaceKey;

        return $this;
    }

    /**
     * {@inheritdoc}
     *
     * Will be called by SuluCollector to collect profiler data.
     */
    public function getUuid(): string
    {
        return $this->course->getId();
    }

    /**
     * {@inheritdoc}
     *
     * Will be called by SuluCollector to collect profiler data.
     */
    public function getPath(): ?string
    {
        return null;
    }

    /**
     * {@inheritdoc}
     *
     * Will be called by SuluCollector to collect profiler data.
     */
    public function getNodeType(): int
    {
        return RedirectType::NONE;
    }

    /**
     * {@inheritdoc}
     *
     * Will be called by SuluCollector to collect profiler data.
     */
    public function getNodeState(): int
    {
        return CourseInterface::WORKFLOW_STAGE_PUBLISHED === $this->course->getWorkflowStage()
            ? WorkflowStage::PUBLISHED
            : WorkflowStage::TEST;
    }

    /**
     * {@inheritdoc}
     *
     * Will be called by SuluCollector to collect profiler data.
     */
    public function getPublished()
    {
        return new \DateTime();
    }

    /**
     * {@inheritdoc}
     *
     * Will be called by SuluCollector to collect profiler data.
     */
    public function getPublishedState()
    {
        return CourseInterface::TRANSITION_PUBLISH === $this->course->getWorkflowStage();
    }

    /**
     * {@inheritdoc}
     *
     * Will be called by SuluCollector to collect profiler data.
     */
    public function getEnabledShadowLanguages()
    {
        return [];
    }

    /**
     * {@inheritdoc}
     *
     * Will be called by SuluCollector to collect profiler data.
     */
    public function getShadowBaseLanguage()
    {
        return null;
    }

    /**
     * {@inheritdoc}
     *
     * Will be called by SuluCollector to collect profiler data.
     */
    public function getHasChildren()
    {
        return false;
    }

    /**
     * {@inheritdoc}
     *
     * Will be called by SuluCollector to collect profiler data.
     */
    public function getCreator(): ?int
    {
        return null;
    }

    /**
     * {@inheritdoc}
     *
     * Will be called by SuluCollector to collect profiler data.
     */
    public function getChanger(): ?int
    {
        return null;
    }

    /**
     * {@inheritdoc}
     *
     * Will be called by SuluCollector to collect profiler data.
     */
    public function getCreated()
    {
        return new \DateTime();
    }

    /**
     * {@inheritdoc}
     *
     * Will be called by SuluCollector to collect profiler data.
     */
    public function getChanged()
    {
        return new \DateTime();
    }

    /**
     * {@inheritdoc}
     *
     * Will be called by SuluCollector to collect profiler data.
     */
    public function getConcreteLanguages()
    {
        return array_map(
            function (TranslationInterface $translation) {
                return $translation->getLocale();
            },
            $this->course->getTranslations()
        );
    }

    /**
     * {@inheritdoc}
     *
     * Will be called by SuluCollector to collect profiler data.
     */
    public function getOriginTemplate()
    {
        return null;
    }

    /**
     * {@inheritdoc}
     *
     * Will be called by SuluCollector to collect profiler data.
     */
    public function getNavContexts()
    {
        return null;
    }
}
