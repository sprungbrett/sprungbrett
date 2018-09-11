<?php

namespace Sprungbrett\Bundle\CourseBundle\Tests\Unit\Model\Attendee\Query;

use PHPUnit\Framework\TestCase;
use Sprungbrett\Bundle\CourseBundle\Model\Attendee\Query\HasBookmarkQuery;

class IsAttendeeQueryTest extends TestCase
{
    public function testGetAttendee()
    {
        $command = new HasBookmarkQuery(42, 'course-123-123-123');

        $this->assertEquals(42, $command->getAttendeeId());
    }

    public function testGetCourseId()
    {
        $command = new HasBookmarkQuery(42, 'course-123-123-123');

        $this->assertEquals('course-123-123-123', $command->getCourseId());
    }
}
