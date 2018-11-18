<?php

declare(strict_types=1);

namespace Sprungbrett\Bundle\ContentBundle\Model\Seo;

use Sprungbrett\Component\Translatable\Model\TranslationInterface;

interface SeoTranslationInterface extends TranslationInterface
{
    public function getTitle(): string;

    public function setTitle(string $title): self;

    public function getDescription(): string;

    public function setDescription(string $description): self;

    public function getKeyWords(): string;

    public function setKeyWords(string $keyWords): self;

    public function getCanonicalUrl(): string;

    public function setCanonicalUrl(string $canonicalUrl): self;

    public function isNoIndex(): bool;

    public function setNoIndex(bool $noIndex): self;

    public function isNoFollow(): bool;

    public function setNoFollow(bool $noFollow): self;

    public function isHideInSitemap(): bool;

    public function setHideInSitemap(bool $hideInSitemap): self;
}
