<?php

namespace Sprungbrett\Component\Translation\Model;

class Localization
{
    /**
     * @var string
     */
    private $locale;

    public function __construct(string $language, ?string $country = null)
    {
        $this->locale = implode('_', array_filter([$language, $country]));
    }

    public function getLocale(): string
    {
        return $this->locale;
    }

    public function getLanguage(): string
    {
        $parts = explode('_', $this->locale);

        return $parts[0];
    }

    public function getCountry(): ?string
    {
        $parts = explode('_', $this->locale);

        return isset($parts[1]) ? $parts[1] : null;
    }

    public function equals(Localization $localization): bool
    {
        return $this->locale === $localization->getLocale();
    }
}
