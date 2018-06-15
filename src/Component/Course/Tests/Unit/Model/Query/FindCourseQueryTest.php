<?php

namespace Sprungbrett\Component\Course\Tests\Unit\Model\Query;

use PHPUnit\Framework\TestCase;
use Sprungbrett\Component\Course\Model\Query\FindCourseQuery;
use Sprungbrett\Component\Uuid\Model\Uuid;

class FindCourseQueryTest extends TestCase
{
    public function testGetUuid()
    {
        $command = new FindCourseQuery('123-123-123');

        $this->assertInstanceOf(Uuid::class, $command->getUuid());
        $this->assertEquals('123-123-123', $command->getUuid()->getId());
    }
}
