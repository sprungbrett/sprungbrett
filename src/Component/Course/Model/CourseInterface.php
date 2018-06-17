<?php

namespace Sprungbrett\Component\Course\Model;

use Sprungbrett\Component\Translation\Model\TranslatableInterface;
use Sprungbrett\Component\Uuid\Model\IdentifiableInterface;

interface CourseInterface extends IdentifiableInterface, TranslatableInterface
{
    public function getTitle(): ?string;

    public function setTitle(string $title): self;

    public function getDescription(): ?string;

    public function setDescription(string $description): self;
}
