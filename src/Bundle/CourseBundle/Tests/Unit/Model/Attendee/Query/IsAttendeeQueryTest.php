<?php

namespace Sprungbrett\Bundle\CourseBundle\Tests\Unit\Model\Attendee\Query;

use PHPUnit\Framework\TestCase;
use Sprungbrett\Bundle\CourseBundle\Model\Attendee\Query\IsAttendeeQuery;

class IsAttendeeQueryTest extends TestCase
{
    public function testGetAttendee()
    {
        $command = new IsAttendeeQuery(42, 'course-123-123-123');

        $this->assertEquals(42, $command->getAttendeeId());
    }

    public function testGetCourseId()
    {
        $command = new IsAttendeeQuery(42, 'course-123-123-123');

        $this->assertEquals('course-123-123-123', $command->getCourseId());
    }
}
