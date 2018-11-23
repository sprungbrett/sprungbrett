<?php

declare(strict_types=1);

namespace Sprungbrett\Bundle\CourseBundle\Tests\Unit\Model\Schedule\Query;

use PHPUnit\Framework\TestCase;
use Sprungbrett\Bundle\CourseBundle\Model\Schedule\Query\ListSchedulesQuery;

class ListSchedulesQueryTest extends TestCase
{
    /**
     * @var ListSchedulesQuery
     */
    private $query;

    protected function setUp()
    {
        $this->query = new ListSchedulesQuery('en', 'app.list-schedules', ['page' => 1]);
    }

    public function testGetLocale(): void
    {
        $this->assertEquals('en', $this->query->getLocale());
    }

    public function testGetRoute(): void
    {
        $this->assertEquals('app.list-schedules', $this->query->getRoute());
    }

    public function testGetQuery(): void
    {
        $this->assertEquals(['page' => 1], $this->query->getQuery());
    }
}
