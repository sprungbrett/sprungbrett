<?php

namespace Sprungbrett\Bundle\CourseBundle\Tests\Unit\Model\Attendee\Handler;

use PHPUnit\Framework\TestCase;
use Sprungbrett\Bundle\CourseBundle\Model\Attendee\AttendeeRepositoryInterface;
use Sprungbrett\Bundle\CourseBundle\Model\Attendee\Handler\HasBookmarkQueryHandler;
use Sprungbrett\Bundle\CourseBundle\Model\Attendee\Query\HasBookmarkQuery;

class IsAttendeeQueryHandlerTest extends TestCase
{
    public function testHandle()
    {
        $repository = $this->prophesize(AttendeeRepositoryInterface::class);
        $handler = new HasBookmarkQueryHandler($repository->reveal());

        $repository->hasBookmark(42, 'course-123-123-123')->willReturn(true);

        $command = $this->prophesize(HasBookmarkQuery::class);
        $command->getCourseId()->willReturn('course-123-123-123');
        $command->getAttendeeId()->willReturn(42);

        $this->assertTrue($handler->handle($command->reveal()));
    }
}
