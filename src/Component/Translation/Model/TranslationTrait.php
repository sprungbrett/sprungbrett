<?php

namespace Sprungbrett\Component\Translation\Model;

trait TranslationTrait
{
    /**
     * @var Localization
     */
    protected $localization;

    protected function initializeTranslation(Localization $localization)
    {
        $this->localization = $localization;
    }

    public function getLocalization(): Localization
    {
        return $this->localization;
    }

    public function getLocale(): string
    {
        return $this->localization->getLocale();
    }
}
