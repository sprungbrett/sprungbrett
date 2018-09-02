<?php

namespace Sprungbrett\Bundle\CourseBundle\Tests\Unit\Model\Attendee\Handler;

use PHPUnit\Framework\TestCase;
use Sprungbrett\Bundle\CourseBundle\Model\Attendee\CourseAttendeeRepositoryInterface;
use Sprungbrett\Bundle\CourseBundle\Model\Attendee\Handler\CountAttendeeQueryHandler;
use Sprungbrett\Bundle\CourseBundle\Model\Attendee\Query\CountAttendeeQuery;

class CountAttendeeQueryHandlerTest extends TestCase
{
    public function testHandle()
    {
        $repository = $this->prophesize(CourseAttendeeRepositoryInterface::class);
        $handler = new CountAttendeeQueryHandler($repository->reveal());

        $repository->countByCourse('123-123-123')->willReturn(42);

        $command = $this->prophesize(CountAttendeeQuery::class);
        $command->getCourseId()->willReturn('123-123-123');

        $this->assertEquals(42, $handler->handle($command->reveal()));
    }
}
