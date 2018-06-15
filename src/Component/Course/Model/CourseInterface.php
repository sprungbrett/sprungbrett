<?php

namespace Sprungbrett\Component\Course\Model;

use Sprungbrett\Component\Uuid\IdentifiableInterface;

interface CourseInterface extends IdentifiableInterface
{
    public function getTitle(): string;

    public function setTitle(string $title): self;
}
