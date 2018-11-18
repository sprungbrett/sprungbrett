<?php

declare(strict_types=1);

namespace Sprungbrett\Bundle\CourseBundle\Model;

use Sprungbrett\Component\Translatable\Model\TranslatableTrait;
use Sprungbrett\Component\Translatable\Model\TranslationInterface;

class Course implements CourseInterface
{
    use TranslatableTrait {
        __construct as protected initializeTranslations;
    }

    /**
     * @var int
     */
    protected $id;

    /**
     * @var string
     */
    protected $uuid;

    /**
     * @var string
     */
    protected $stage;

    /**
     * @param CourseTranslationInterface[] $translations
     */
    public function __construct(string $uuid, string $stage, ?array $translations = null)
    {
        $this->uuid = $uuid;
        $this->stage = $stage;

        $this->initializeTranslations($translations);
    }

    public function getUuid(): string
    {
        return $this->uuid;
    }

    public function getStage(): string
    {
        return $this->stage;
    }

    public function getName(?string $locale = null): ?string
    {
        return $this->getTranslation($locale)->getName();
    }

    public function setName(?string $name, ?string $locale = null): CourseInterface
    {
        $this->getTranslation($locale)->setName($name);

        return $this;
    }

    public function getTranslation(?string $locale = null): CourseTranslationInterface
    {
        /** @var CourseTranslationInterface $translation */
        $translation = $this->doGetTranslation($locale);

        return $translation;
    }

    protected function createTranslation(string $locale): TranslationInterface
    {
        return new CourseTranslation($this, $locale);
    }
}
