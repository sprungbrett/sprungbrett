<?php

declare(strict_types=1);

namespace Sprungbrett\Bundle\ContentBundle\Model\Seo;

use Sprungbrett\Bundle\ContentBundle\Stages;
use Sprungbrett\Component\Translatable\Model\TranslatableTrait;
use Sprungbrett\Component\Translatable\Model\TranslationInterface;

class Seo implements SeoInterface
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

    public function getTitle(?string $locale = null): string
    {
        return $this->getTranslation($locale)->getTitle();
    }

    public function setTitle(string $title, ?string $locale = null): SeoInterface
    {
        $this->getTranslation($locale)->setTitle($title);

        return $this;
    }

    public function getDescription(?string $locale = null): string
    {
        return $this->getTranslation($locale)->getDescription();
    }

    public function setDescription(string $description, ?string $locale = null): SeoInterface
    {
        $this->getTranslation($locale)->setDescription($description);

        return $this;
    }

    public function getKeyWords(?string $locale = null): string
    {
        return $this->getTranslation($locale)->getKeyWords();
    }

    public function setKeyWords(string $keyWords, ?string $locale = null): SeoInterface
    {
        $this->getTranslation($locale)->setKeyWords($keyWords);

        return $this;
    }

    public function getCanonicalUrl(?string $locale = null): string
    {
        return $this->getTranslation($locale)->getCanonicalUrl();
    }

    public function setCanonicalUrl(string $canonicalUrl, ?string $locale = null): SeoInterface
    {
        $this->getTranslation($locale)->setCanonicalUrl($canonicalUrl);

        return $this;
    }

    public function isNoIndex(?string $locale = null): bool
    {
        return $this->getTranslation($locale)->isNoIndex();
    }

    public function setNoIndex(bool $noIndex, ?string $locale = null): SeoInterface
    {
        $this->getTranslation($locale)->setNoIndex($noIndex);

        return $this;
    }

    public function isNoFollow(?string $locale = null): bool
    {
        return $this->getTranslation($locale)->isNoFollow();
    }

    public function setNoFollow(bool $noFollow, ?string $locale = null): SeoInterface
    {
        $this->getTranslation($locale)->setNoFollow($noFollow);

        return $this;
    }

    public function isHideInSitemap(?string $locale = null): bool
    {
        return $this->getTranslation($locale)->isHideInSitemap();
    }

    public function setHideInSitemap(bool $hideInSitemap, ?string $locale = null): SeoInterface
    {
        $this->getTranslation($locale)->setHideInSitemap($hideInSitemap);

        return $this;
    }

    public function getTranslation(?string $locale = null): SeoTranslationInterface
    {
        /** @var SeoTranslationInterface $translation */
        $translation = $this->doGetTranslation($locale);

        return $translation;
    }

    protected function createTranslation(string $locale): TranslationInterface
    {
        return new SeoTranslation($this, $locale);
    }
}
