<?php

namespace Sprungbrett\Component\Course\Model;

use Doctrine\Common\Collections\Collection;
use Sprungbrett\Component\Translation\Model\Exception\MissingLocalizationException;
use Sprungbrett\Component\Translation\Model\Localization;
use Sprungbrett\Component\Translation\Model\TranslatableTrait;
use Sprungbrett\Component\Translation\Model\TranslationInterface;
use Sprungbrett\Component\Uuid\Model\Uuid;
use Sprungbrett\Component\Uuid\Model\UuidTrait;

class Course implements CourseInterface
{
    use UuidTrait;
    use TranslatableTrait;

    public function __construct(?Collection $translations = null, ?Uuid $uuid = null)
    {
        $this->initializeUuid($uuid);
        $this->initializeTranslations($translations);
    }

    public function getTitle(?Localization $localization = null): ?string
    {
        return $this->getTranslation($localization)->getTitle();
    }

    public function setTitle(string $title, ?Localization $localization = null): CourseInterface
    {
        $this->getTranslation($localization)->setTitle($title);

        return $this;
    }

    /**
     * @throws MissingLocalizationException
     */
    public function getTranslation(?Localization $localization = null): CourseTranslationInterface
    {
        /** @var CourseTranslationInterface $translation */
        $translation = $this->doGetTranslation($localization);

        return $translation;
    }

    protected function createTranslation(Localization $localization): TranslationInterface
    {
        return new CourseTranslation($localization);
    }
}
