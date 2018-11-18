<?php

declare(strict_types=1);

namespace Sprungbrett\Bundle\ContentBundle\Model\Content;

class ContentTranslation implements ContentTranslationInterface
{
    /**
     * @var ContentInterface
     */
    private $content;

    /**
     * @var string
     */
    private $locale;

    /**
     * @var string|null
     */
    protected $type;

    /**
     * @var array
     */
    protected $data = [];

    public function __construct(ContentInterface $content, string $locale)
    {
        $this->content = $content;
        $this->locale = $locale;
    }

    public function getContent(): ContentInterface
    {
        return $this->content;
    }

    public function getLocale(): string
    {
        return $this->locale;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function getData(): array
    {
        return $this->data;
    }

    public function modifyData(?string $type, array $data): ContentTranslationInterface
    {
        $this->type = $type;
        $this->data = $data;

        return $this;
    }
}
