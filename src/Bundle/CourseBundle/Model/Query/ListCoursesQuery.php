<?php

namespace Sprungbrett\Bundle\CourseBundle\Model\Query;

class ListCoursesQuery
{
    /**
     * @var string
     */
    private $locale;

    /**
     * @var string
     */
    private $route;

    /**
     * @var array
     */
    private $query;

    public function __construct(string $locale, string $route, array $query)
    {
        $this->locale = $locale;
        $this->route = $route;
        $this->query = $query;
    }

    public function getLocale(): string
    {
        return $this->locale;
    }

    public function getRoute(): string
    {
        return $this->route;
    }

    public function getQuery(): array
    {
        return $this->query;
    }
}
