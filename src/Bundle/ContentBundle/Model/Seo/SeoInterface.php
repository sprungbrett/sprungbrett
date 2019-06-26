<?php

declare(strict_types=1);

namespace Sprungbrett\Bundle\ContentBundle\Model\Seo;

use Sprungbrett\Component\Translatable\Model\TranslatableInterface;

interface SeoInterface extends TranslatableInterface
{
    public function getResourceKey(): string;

    public function getResourceId(): string;

    public function getStage(): string;

    public function getTitle(?string $locale = null): string;

    public function setTitle(string $title, ?string $locale = null): self;

    public function getDescription(?string $locale = null): string;

    public function setDescription(string $description, ?string $locale = null): self;

    public function getKeyWords(?string $locale = null): string;

    public function setKeyWords(string $keyWords, ?string $locale = null): self;

    public function getCanonicalUrl(?string $locale = null): string;

    public function setCanonicalUrl(string $canonicalUrl, ?string $locale = null): self;

    public function isNoIndex(?string $locale = null): bool;

    public function setNoIndex(bool $noIndex, ?string $locale = null): self;

    public function isNoFollow(?string $locale = null): bool;

    public function setNoFollow(bool $noFollow, ?string $locale = null): self;

    public function isHideInSitemap(?string $locale = null): bool;

    public function setHideInSitemap(bool $hideInSitemap, ?string $locale = null): self;

    public function getTranslation(?string $locale = null): SeoTranslationInterface;
}
