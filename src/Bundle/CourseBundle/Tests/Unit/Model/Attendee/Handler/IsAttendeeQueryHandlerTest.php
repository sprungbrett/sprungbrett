<?php

namespace Sprungbrett\Bundle\CourseBundle\Tests\Unit\Model\Attendee\Handler;

use PHPUnit\Framework\TestCase;
use Sprungbrett\Bundle\CourseBundle\Model\Attendee\CourseAttendeeRepositoryInterface;
use Sprungbrett\Bundle\CourseBundle\Model\Attendee\Handler\IsAttendeeQueryHandler;
use Sprungbrett\Bundle\CourseBundle\Model\Attendee\Query\IsAttendeeQuery;

class IsAttendeeQueryHandlerTest extends TestCase
{
    public function testHandle()
    {
        $repository = $this->prophesize(CourseAttendeeRepositoryInterface::class);
        $handler = new IsAttendeeQueryHandler($repository->reveal());

        $repository->exists(42, 'course-123-123-123')->willReturn(true);

        $command = $this->prophesize(IsAttendeeQuery::class);
        $command->getCourseId()->willReturn('course-123-123-123');
        $command->getAttendeeId()->willReturn(42);

        $this->assertTrue($handler->handle($command->reveal()));
    }
}
