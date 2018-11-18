<?php

declare(strict_types=1);

namespace Sprungbrett\Component\Translatable\Model;

trait TranslationTrait
{
    /**
     * @var string
     */
    protected $locale;

    public function __construct(string $locale)
    {
        $this->locale = $locale;
    }

    public function getLocale(): string
    {
        return $this->locale;
    }
}
