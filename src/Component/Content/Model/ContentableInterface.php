<?php

namespace Sprungbrett\Component\Content\Model;

interface ContentableInterface
{
    public function getStructureType(): string;

    public function setStructureType(string $structureType): self;

    public function getContentData(): array;

    public function setContentData(array $contentData): self;
}
