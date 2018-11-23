<?php

declare(strict_types=1);

namespace Sprungbrett\Bundle\CourseBundle\Tests\Unit\Model\Schedule\Query;

use PHPUnit\Framework\TestCase;
use Sprungbrett\Bundle\ContentBundle\Stages;
use Sprungbrett\Bundle\CourseBundle\Model\Schedule\Query\FindScheduleQuery;

class FindScheduleQueryTest extends TestCase
{
    /**
     * @var FindScheduleQuery
     */
    private $query;

    protected function setUp()
    {
        $this->query = new FindScheduleQuery('123-123-123', 'en', Stages::LIVE);
    }

    public function testGetUuid(): void
    {
        $this->assertEquals('123-123-123', $this->query->getUuid());
    }

    public function testGetLocale(): void
    {
        $this->assertEquals('en', $this->query->getLocale());
    }

    public function testGetStage(): void
    {
        $this->assertEquals(Stages::LIVE, $this->query->getStage());
    }
}
