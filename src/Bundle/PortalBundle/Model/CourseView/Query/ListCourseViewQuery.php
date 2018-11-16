<?php

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

    public function __construct(int $page, int $pageSize = self::DEFAULT_PAGE_SIZE)
    {
        $this->page = $page;
        $this->pageSize = $pageSize;
    }

    public function getPage(): int
    {
        return $this->page;
    }

    public function getPageSize(): int
    {
        return $this->pageSize;
    }
}
