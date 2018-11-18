<?php

declare(strict_types=1);

namespace Sprungbrett\Bundle\PortalBundle\Tests\Unit\Model\CourseView\Query;

use PHPUnit\Framework\TestCase;
use Sprungbrett\Bundle\PortalBundle\Model\CourseView\Query\ListCourseViewQuery;

class ListCourseViewQueryTest extends TestCase
{
    /**
     * @var ListCourseViewQuery
     */
    private $query;

    protected function setUp()
    {
        $this->query = new ListCourseViewQuery('en', 1, 10);
    }

    public function testGetLocale(): void
    {
        $this->assertEquals('en', $this->query->getLocale());
    }

    public function testGetPage(): void
    {
        $this->assertEquals(1, $this->query->getPage());
    }

    public function testGetPageSize(): void
    {
        $this->assertEquals(10, $this->query->getPageSize());
    }
}
