<?php

declare(strict_types=1);

namespace Sprungbrett\Bundle\CourseBundle\Model\Message;

class PublishCourseMessage
{
    /**
     * @var string
     */
    private $uuid;

    /**
     * @var string
     */
    private $locale;

    public function __construct(string $uuid, string $locale)
    {
        $this->uuid = $uuid;
        $this->locale = $locale;
    }

    public function getUuid(): string
    {
        return $this->uuid;
    }

    public function getLocale(): string
    {
        return $this->locale;
    }
}
