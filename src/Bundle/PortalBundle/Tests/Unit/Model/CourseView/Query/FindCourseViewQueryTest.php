<?php

declare(strict_types=1);

namespace Sprungbrett\Bundle\PortalBundle\Tests\Unit\Model\CourseView\Query;

use PHPUnit\Framework\TestCase;
use Sprungbrett\Bundle\PortalBundle\Model\CourseView\Query\FindCourseViewQuery;

class FindCourseViewQueryTest extends TestCase
{
    /**
     * @var FindCourseViewQuery
     */
    private $query;

    protected function setUp()
    {
        $this->query = new FindCourseViewQuery('123-123-123', 'en');
    }

    public function testGetUuid(): void
    {
        $this->assertEquals('123-123-123', $this->query->getUuid());
    }

    public function testGetLocale(): void
    {
        $this->assertEquals('en', $this->query->getLocale());
    }
}
