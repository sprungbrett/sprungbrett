<?php

declare(strict_types=1);

namespace Sprungbrett\Bundle\CourseBundle\Tests\Unit\Model\Query;

use PHPUnit\Framework\TestCase;
use Sprungbrett\Bundle\CourseBundle\Model\Query\ListCoursesQuery;

class ListCoursesQueryTest extends TestCase
{
    /**
     * @var ListCoursesQuery
     */
    private $query;

    protected function setUp()
    {
        $this->query = new ListCoursesQuery('en', 'app.list-courses', ['page' => 1]);
    }

    public function testGetLocale(): void
    {
        $this->assertEquals('en', $this->query->getLocale());
    }

    public function testGetRoute(): void
    {
        $this->assertEquals('app.list-courses', $this->query->getRoute());
    }

    public function testGetQuery(): void
    {
        $this->assertEquals(['page' => 1], $this->query->getQuery());
    }
}
