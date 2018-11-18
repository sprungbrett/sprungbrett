<?php

declare(strict_types=1);

namespace Sprungbrett\Bundle\PortalBundle\Model\CourseView\Query;

class FindCourseViewQuery
{
    /**
     * @var string
     */
    protected $uuid;

    /**
     * @var string
     */
    protected $locale;

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
