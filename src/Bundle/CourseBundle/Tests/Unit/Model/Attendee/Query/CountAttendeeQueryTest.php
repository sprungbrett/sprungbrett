<?php

namespace Sprungbrett\Bundle\CourseBundle\Tests\Unit\Model\Attendee\Query;

use PHPUnit\Framework\TestCase;
use Sprungbrett\Bundle\CourseBundle\Model\Attendee\Query\CountBookmarksQuery;

class CountAttendeeQueryTest extends TestCase
{
    public function testGetCourseId()
    {
        $command = new CountBookmarksQuery('123-123-123');

        $this->assertEquals('123-123-123', $command->getCourseId());
    }
}
