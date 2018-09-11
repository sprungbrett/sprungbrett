<?php

namespace Sprungbrett\Bundle\CourseBundle\Tests\Unit\Model\Attendee\Command;

use PHPUnit\Framework\TestCase;
use Sprungbrett\Bundle\CourseBundle\Model\Attendee\Command\BookmarkCourseCommand;
use Sprungbrett\Component\Translation\Model\Localization;

class BookmarkCourseCommandTest extends TestCase
{
    public function testGetAttendeeId()
    {
        $command = new BookmarkCourseCommand(42, 'course-123-123-123', 'de');

        $this->assertEquals(42, $command->getAttendeeId());
    }

    public function testGetCourseId()
    {
        $command = new BookmarkCourseCommand(42, 'course-123-123-123', 'de');

        $this->assertEquals('course-123-123-123', $command->getCourseId());
    }

    public function testGetLocalization()
    {
        $command = new BookmarkCourseCommand(42, 'course-123-123-123', 'de');

        $this->assertInstanceOf(Localization::class, $command->getLocalization());
        $this->assertEquals('de', $command->getLocalization()->getLocale());
    }
}