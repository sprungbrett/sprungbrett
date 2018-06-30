<?php

namespace Sprungbrett\Component\Content\Model;

trait ContentableTrait
{
    /**
     * @var ContentInterface
     */
    protected $content;

    protected function initializeContent(ContentInterface $content): void
    {
        $this->content = $content;
    }

    public function getStructureType(): string
    {
        return $this->content->getStructureType();
    }

    public function setStructureType(string $structureType): ContentableInterface
    {
        $this->content->setStructureType($structureType);

        return $this;
    }

    public function getContentData(): array
    {
        return $this->content->getContentData();
    }

    public function setContentData(array $contentData): ContentableInterface
    {
        $this->content->setContentData($contentData);

        return $this;
    }
}
