<?php

declare(strict_types=1);

namespace Sprungbrett\Bundle\ContentBundle\Model\Content;

use Sprungbrett\Bundle\ContentBundle\Stages;
use Sprungbrett\Component\Translatable\Model\TranslatableTrait;
use Sprungbrett\Component\Translatable\Model\TranslationInterface;

class Content implements ContentInterface
{
    use TranslatableTrait {
        __construct as protected initializeTranslations;
    }

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
    protected $stage;

    public function __construct(
        string $resourceKey,
        string $resourceId,
        string $stage = Stages::DRAFT,
        array $translations = []
    ) {
        $this->resourceKey = $resourceKey;
        $this->resourceId = $resourceId;
        $this->stage = $stage;

        $this->initializeTranslations($translations);
    }

    public function getResourceKey(): string
    {
        return $this->resourceKey;
    }

    public function getResourceId(): string
    {
        return $this->resourceId;
    }

    public function getStage(): string
    {
        return $this->stage;
    }

    public function getType(?string $locale = null): ?string
    {
        return $this->getTranslation($locale)->getType();
    }

    public function getData(?string $locale = null): array
    {
        return $this->getTranslation($locale)->getData();
    }

    public function modifyData(?string $type, array $data, ?string $locale = null): ContentInterface
    {
        $this->getTranslation($locale)->modifyData($type, $data);

        return $this;
    }

    public function getTranslation(?string $locale = null): ContentTranslationInterface
    {
        /** @var ContentTranslationInterface $translation */
        $translation = $this->doGetTranslation($locale);

        return $translation;
    }

    protected function createTranslation(string $locale): TranslationInterface
    {
        return new ContentTranslation($this, $locale);
    }
}
