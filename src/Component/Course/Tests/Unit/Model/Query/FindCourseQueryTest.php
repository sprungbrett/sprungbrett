<?php

namespace Sprungbrett\Component\Course\Tests\Unit\Model\Query;

use PHPUnit\Framework\TestCase;
use Sprungbrett\Component\Course\Model\Query\FindCourseQuery;
use Sprungbrett\Component\Translation\Model\Localization;
use Sprungbrett\Component\Uuid\Model\Uuid;

class FindCourseQueryTest extends TestCase
{
    public function testGetUuid()
    {
        $command = new FindCourseQuery('123-123-123', 'de');

        $this->assertInstanceOf(Uuid::class, $command->getUuid());
        $this->assertEquals('123-123-123', $command->getUuid()->getId());
    }

    public function testGetLocale()
    {
        $command = new FindCourseQuery('123-123-123', 'de');

        $this->assertInstanceOf(Localization::class, $command->getLocalization());
        $this->assertEquals('de', $command->getLocalization()->getLocale());
    }
}
