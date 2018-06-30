<?php

namespace Sprungbrett\Bundle\CourseBundle\Tests\Unit\Model\Course\Query;

use PHPUnit\Framework\TestCase;
use Sprungbrett\Bundle\CourseBundle\Model\Course\Query\FindCourseQuery;
use Sprungbrett\Component\Translation\Model\Localization;

class FindCourseQueryTest extends TestCase
{
    public function getId()
    {
        $command = new FindCourseQuery('123-123-123', 'de');

        $this->assertEquals('123-123-123', $command->getId());
    }

    public function testGetLocale()
    {
        $command = new FindCourseQuery('123-123-123', 'de');

        $this->assertInstanceOf(Localization::class, $command->getLocalization());
        $this->assertEquals('de', $command->getLocalization()->getLocale());
    }
}
