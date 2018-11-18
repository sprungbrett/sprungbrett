<?php

declare(strict_types=1);

namespace Sprungbrett\Component\Translatable\Model;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Sprungbrett\Component\Translatable\Model\Exception\MissingLocaleException;

trait TranslatableTrait
{
    /**
     * @var Collection|TranslationInterface[]
     */
    protected $translations;

    /**
     * @var string
     */
    protected $currentLocale;

    public function __construct(?array $translations = null)
    {
        $this->translations = new ArrayCollection($translations ?: []);
    }

    public function setCurrentLocale(string $currentLocale): void
    {
        $this->currentLocale = $currentLocale;
    }

    public function getCurrentLocale(): ?string
    {
        return $this->currentLocale;
    }

    protected function doGetTranslation(?string $locale = null): TranslationInterface
    {
        $locale = $locale ?: $this->currentLocale;
        if (!$locale) {
            throw new MissingLocaleException();
        }

        if ($this->translations->containsKey($locale)) {
            return $this->translations->get($locale);
        }

        $translation = $this->createTranslation($locale);
        $this->translations->set($locale, $translation);

        return $translation;
    }

    abstract protected function createTranslation(string $locale): TranslationInterface;
}
