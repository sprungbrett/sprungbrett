<?php

namespace Sprungbrett\Component\Content\Model;

class Content implements ContentInterface
{
    /**
     * @var string
     */
    protected $structureType;

    /**
     * @var array
     */
    protected $contentData;

    public function getStructureType(): string
    {
        return $this->structureType ?: '';
    }

    public function setStructureType(string $structureType): ContentInterface
    {
        $this->structureType = $structureType;

        return $this;
    }

    public function getContentData(): array
    {
        return $this->contentData ?: [];
    }

    public function setContentData(array $contentData): ContentInterface
    {
        $this->contentData = $contentData;

        return $this;
    }
}
