<?php

namespace Sprungbrett\Bundle\CourseBundle\Model\Course;

use Sprungbrett\Component\Content\Model\ContentableInterface;
use Sprungbrett\Component\Translation\Model\TranslatableInterface;

interface CourseInterface extends TranslatableInterface, ContentableInterface
{
    const WORKFLOW_STAGE_NEW = 'new';
    const WORKFLOW_STAGE_TEST = 'test';
    const WORKFLOW_STAGE_PUBLISHED = 'published';

    const TRANSITION_CREATE = 'create';
    const TRANSITION_PUBLISH = 'publish';
    const TRANSITION_UNPUBLISH = 'unpublish';

    const PHASE_CREATION = 'creation';
    const PHASE_REGISTRATION = 'registration';
    const PHASE_RESERVATION = 'reservation';
    const PHASE_EVALUATION = 'post';
    const PHASE_CLOSED = 'closed';

    const PHASE_TRANSITION_START_REGISTRATION = 'start_registration';
    const PHASE_TRANSITION_START_RESERVATION = 'start_reservation';
    const PHASE_TRANSITION_START_EVALUATION = 'held';
    const PHASE_TRANSITION_CLOSE = 'close';

    public function __construct(string $id, ?array $translations = null);

    public function getId(): string;

    public function getTrainerId(): ?int;

    public function setTrainerId(?int $trainerId): self;

    public function getPhase(): string;

    public function setPhase(string $phase): CourseInterface;

    public function getWorkflowStage(): string;

    public function setWorkflowStage(string $workflowStage): self;

    public function getName(): ?string;

    public function setName(string $name): self;

    public function getDescription(): ?string;

    public function setDescription(string $description): self;

    public function getTranslations(): array;

    /**
     * FIXME remove from here (not domain logic).
     */
    public function removeRoute(): self;

    /**
     * FIXME remove from here (not domain logic).
     */
    public function getRoutePath(): ?string;
}
