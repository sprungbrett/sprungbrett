<?php

namespace Sprungbrett\Bundle\CourseBundle\Model\Course;

use Doctrine\Common\Collections\Collection;
use Sprungbrett\Component\Translation\Model\TranslatableInterface;

interface CourseInterface extends TranslatableInterface
{
    const WORKFLOW_STAGE_NEW = 'new';
    const WORKFLOW_STAGE_TEST = 'test';
    const WORKFLOW_STAGE_PUBLISHED = 'published';

    const TRANSITION_CREATE = 'create';
    const TRANSITION_PUBLISH = 'publish';
    const TRANSITION_UNPUBLISH = 'unpublish';

    public function __construct(string $id, ?Collection $translations = null);

    public function getId(): string;

    public function getTrainerId(): ?int;

    public function setTrainerId(?int $trainerId): self;

    public function getWorkflowStage(): string;

    public function setWorkflowStage(string $workflowStage): self;

    public function getTitle(): ?string;

    public function setTitle(string $title): self;

    public function getDescription(): ?string;

    public function setDescription(string $description): self;

    public function getStructureType(): string;

    public function setStructureType(string $structureType): self;

    public function getContentData(): array;

    public function setContentData(array $contentData): self;

    /**
     * FIXME remove from here (not domain logic).
     */
    public function removeRoute(): self;
}
