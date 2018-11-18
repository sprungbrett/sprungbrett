<?php

declare(strict_types=1);

namespace Sprungbrett\Bundle\CourseBundle\Model\Event;

use Sprungbrett\Bundle\CourseBundle\Model\CourseInterface;

class CoursePublishedEvent
{
    /**
     * @var string
     */
    protected $locale;

    /**
     * @var CourseInterface
     */
    protected $course;

    public function __construct(string $locale, CourseInterface $course)
    {
        $this->locale = $locale;
        $this->course = $course;
    }

    public function getUuid()
    {
        return $this->course->getUuid();
    }

    public function getLocale()
    {
        return $this->locale;
    }
}
