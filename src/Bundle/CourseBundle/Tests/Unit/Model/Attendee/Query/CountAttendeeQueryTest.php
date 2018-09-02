<?php

namespace Sprungbrett\Bundle\CourseBundle\Tests\Unit\Model\Attendee\Query;

use PHPUnit\Framework\TestCase;
use Sprungbrett\Bundle\CourseBundle\Model\Attendee\Query\CountAttendeeQuery;

class CountAttendeeQueryTest extends TestCase
{
    public function testGetCourseId()
    {
        $command = new CountAttendeeQuery('123-123-123');

        $this->assertEquals('123-123-123', $command->getCourseId());
    }
}
