<?php

declare(strict_types=1);

namespace Sprungbrett\Bundle\CourseBundle\Tests\Unit\Model\Query;

use PHPUnit\Framework\TestCase;
use Sprungbrett\Bundle\ContentBundle\Stages;
use Sprungbrett\Bundle\CourseBundle\Model\Query\FindCourseQuery;

class FindCourseQueryTest extends TestCase
{
    /**
     * @var FindCourseQuery
     */
    private $query;

    protected function setUp()
    {
        $this->query = new FindCourseQuery('123-123-123', 'en', Stages::LIVE);
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
