<?php

namespace Sprungbrett\Bundle\CourseBundle\Tests\Unit\Model\Attendee\Handler;

use PHPUnit\Framework\TestCase;
use Sprungbrett\Bundle\CourseBundle\Model\Attendee\AttendeeRepositoryInterface;
use Sprungbrett\Bundle\CourseBundle\Model\Attendee\Handler\CountBookmarkQueryHandler;
use Sprungbrett\Bundle\CourseBundle\Model\Attendee\Query\CountBookmarksQuery;

class CountAttendeeQueryHandlerTest extends TestCase
{
    public function testHandle()
    {
        $repository = $this->prophesize(AttendeeRepositoryInterface::class);
        $handler = new CountBookmarkQueryHandler($repository->reveal());

        $repository->countBookmarks('123-123-123')->willReturn(42);

        $command = $this->prophesize(CountBookmarksQuery::class);
        $command->getCourseId()->willReturn('123-123-123');

        $this->assertEquals(42, $handler->handle($command->reveal()));
    }
}
