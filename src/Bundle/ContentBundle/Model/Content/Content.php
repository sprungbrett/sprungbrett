<?php

declare(strict_types=1);

namespace Sprungbrett\Bundle\ContentBundle\Model\Content;

/**
 * FIXME extract ContentTranslation.
 */
class Content implements ContentInterface
{
    /**
     * @var int
     */
    protected $id;

    /**
     * @var string
     */
    protected $resourceKey;

    /**
     * @var string
     */
    protected $resourceId;

    /**
     * @var string
     */
    protected $locale;

    /**
     * @var string
     *
     * TODO move to translation
     */
    protected $stage;

    /**
     * @var string|null
     */
    protected $type;

    /**
     * @var array
     */
    protected $data;

    public function __construct(string $resourceKey, string $resourceId, string $locale, string $stage)
    {
        $this->resourceKey = $resourceKey;
        $this->resourceId = $resourceId;
        $this->locale = $locale;
        $this->stage = $stage;
    }

    public function getResourceKey(): string
    {
        return $this->resourceKey;
    }

    public function getResourceId(): string
    {
        return $this->resourceId;
    }

    public function getLocale(): string
    {
        return $this->locale;
    }

    public function getStage(): string
    {
        return $this->stage;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function getData(): array
    {
        return $this->data;
    }

    public function modifyData(?string $type, array $data): ContentInterface
    {
        $this->type = $type;
        $this->data = $data;

        return $this;
    }
}
