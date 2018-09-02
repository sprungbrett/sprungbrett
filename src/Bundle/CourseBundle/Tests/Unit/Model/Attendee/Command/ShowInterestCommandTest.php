<?php

namespace Sprungbrett\Bundle\CourseBundle\Tests\Unit\Model\Attendee\Command;

use PHPUnit\Framework\TestCase;
use Sprungbrett\Bundle\CourseBundle\Model\Attendee\Command\ShowInterestCommand;

class ShowInterestCommandTest extends TestCase
{
    public function testGetAttendeeId()
    {
        $command = new ShowInterestCommand(42, 'course-123-123-123');

        $this->assertEquals(42, $command->getAttendeeId());
    }

    public function testGetCourseId()
    {
        $command = new ShowInterestCommand(42, 'course-123-123-123');

        $this->assertEquals('course-123-123-123', $command->getCourseId());
    }
}
