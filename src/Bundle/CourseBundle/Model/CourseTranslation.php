<?php

declare(strict_types=1);

namespace Sprungbrett\Bundle\CourseBundle\Model;

use Sprungbrett\Component\Translatable\Model\TranslationTrait;

class CourseTranslation implements CourseTranslationInterface
{
    use TranslationTrait{
        __construct as protected initializeLocale;
    }

    /**
     * @var int
     */
    protected $id;

    /**
     * @var CourseInterface
     */
    protected $course;

    /**
     * @var string|null
     */
    protected $name;

    public function __construct(CourseInterface $course, string $locale)
    {
        $this->course = $course;

        $this->initializeLocale($locale);
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): CourseTranslationInterface
    {
        $this->name = $name;

        return $this;
    }
}
