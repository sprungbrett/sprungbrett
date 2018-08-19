<?php

namespace Sprungbrett\Component\Translation\Model;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Sprungbrett\Component\Translation\Model\Exception\MissingLocalizationException;

trait TranslatableTrait
{
    /**
     * @var Collection|TranslationInterface[]
     */
    protected $translations;

    /**
     * @var Localization
     */
    protected $currentLocalization;

    protected function initializeTranslations(?Collection $translations = null)
    {
        $this->translations = $translations ?: new ArrayCollection();
    }

    public function setCurrentLocalization(Localization $currentLocalization): void
    {
        $this->currentLocalization = $currentLocalization;
    }

    public function getLocalization(): ?Localization
    {
        return $this->currentLocalization;
    }

    public function getLocale(?Localization $localization = null): string
    {
        return $this->doGetTranslation($localization)->getLocale();
    }

    protected function doGetTranslation(?Localization $localization = null): TranslationInterface
    {
        $localization = $localization ?: $this->currentLocalization;
        if (!$localization) {
            throw new MissingLocalizationException();
        }

        foreach ($this->translations as $translation) {
            if ($localization->equals($translation->getLocalization())) {
                return $translation;
            }
        }

        $translation = $this->createTranslation($localization);
        $this->translations->add($translation);

        return $translation;
    }

    abstract protected function createTranslation(Localization $localization): TranslationInterface;
}
