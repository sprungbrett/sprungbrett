<?php

declare(strict_types=1);

namespace Sprungbrett\Bundle\PortalBundle\Model\CourseView\Query;

class ListCourseViewQuery
{
    const DEFAULT_PAGE_SIZE = 10;

    /**
     * @var int
     */
    private $page;

    /**
     * @var int
     */
    private $pageSize;

    /**
     * @var string
     */
    private $locale;

    public function __construct(string $locale, int $page, int $pageSize = self::DEFAULT_PAGE_SIZE)
    {
        $this->page = $page;
        $this->pageSize = $pageSize;
        $this->locale = $locale;
    }

    public function getPage(): int
    {
        return $this->page;
    }

    public function getPageSize(): int
    {
        return $this->pageSize;
    }

    public function getLocale(): string
    {
        return $this->locale;
    }
}
