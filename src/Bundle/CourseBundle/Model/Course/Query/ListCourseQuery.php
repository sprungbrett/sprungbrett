<?php

namespace Sprungbrett\Bundle\CourseBundle\Model\Course\Query;

use Sprungbrett\Component\Resource\Model\Command\ListQueryInterface;

class ListCourseQuery implements ListQueryInterface
{
    /**
     * @var string
     */
    private $entityClass;

    /**
     * @var string
     */
    private $resourceKey;

    /**
     * @var string|null
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

    public function __construct(string $entityClass, string $resourceKey, ?string $locale, string $route, array $query)
    {
        $this->entityClass = $entityClass;
        $this->resourceKey = $resourceKey;
        $this->locale = $locale;
        $this->route = $route;
        $this->query = $query;
    }

    public function getEntityClass(): string
    {
        return $this->entityClass;
    }

    public function getResourceKey(): string
    {
        return $this->resourceKey;
    }

    public function getLocale(): ?string
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
