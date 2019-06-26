<?php

declare(strict_types=1);

namespace Sprungbrett\Bundle\ContentBundle\Model\Seo;

class SeoTranslation implements SeoTranslationInterface
{
    /**
     * @var SeoInterface
     */
    protected $seo;

    /**
     * @var string
     */
    protected $locale;

    /**
     * @var string
     */
    protected $title;

    /**
     * @var string
     */
    protected $description;

    /**
     * @var string
     */
    protected $keyWords;

    /**
     * @var string
     */
    protected $canonicalUrl;

    /**
     * @var bool
     */
    protected $noIndex;

    /**
     * @var bool
     */
    protected $noFollow;

    /**
     * @var bool
     */
    protected $hideInSitemap;

    public function __construct(SeoInterface $seo, string $locale)
    {
        $this->seo = $seo;
        $this->locale = $locale;
    }

    public function getLocale(): string
    {
        return $this->locale;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): SeoTranslationInterface
    {
        $this->title = $title;

        return $this;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setDescription(string $description): SeoTranslationInterface
    {
        $this->description = $description;

        return $this;
    }

    public function getKeyWords(): string
    {
        return $this->keyWords;
    }

    public function setKeyWords(string $keyWords): SeoTranslationInterface
    {
        $this->keyWords = $keyWords;

        return $this;
    }

    public function getCanonicalUrl(): string
    {
        return $this->canonicalUrl;
    }

    public function setCanonicalUrl(string $canonicalUrl): SeoTranslationInterface
    {
        $this->canonicalUrl = $canonicalUrl;

        return $this;
    }

    public function isNoIndex(): bool
    {
        return $this->noIndex;
    }

    public function setNoIndex(bool $noIndex): SeoTranslationInterface
    {
        $this->noIndex = $noIndex;

        return $this;
    }

    public function isNoFollow(): bool
    {
        return $this->noFollow;
    }

    public function setNoFollow(bool $noFollow): SeoTranslationInterface
    {
        $this->noFollow = $noFollow;

        return $this;
    }

    public function isHideInSitemap(): bool
    {
        return $this->hideInSitemap;
    }

    public function setHideInSitemap(bool $hideInSitemap): SeoTranslationInterface
    {
        $this->hideInSitemap = $hideInSitemap;

        return $this;
    }
}
