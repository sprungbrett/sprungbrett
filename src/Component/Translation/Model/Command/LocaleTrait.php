<?php

namespace Sprungbrett\Component\Translation\Model\Command;

use Sprungbrett\Component\Translation\Model\Localization;

trait LocaleTrait
{
    /**
     * @var string
     */
    protected $locale;

    public function initializeLocale(string $locale): void
    {
        $this->locale = $locale;
    }

    public function getLocalization(): Localization
    {
        return new Localization($this->locale);
    }
}
