<?php

namespace Sprungbrett\Bundle\CourseBundle\Tests\Unit\Model\Attendee\Handler;

use PHPUnit\Framework\TestCase;
use Sprungbrett\Bundle\CourseBundle\Model\Attendee\AttendeeInterface;
use Sprungbrett\Bundle\CourseBundle\Model\Attendee\AttendeeRepositoryInterface;
use Sprungbrett\Bundle\CourseBundle\Model\Attendee\Handler\FindAttendeeQueryHandler;
use Sprungbrett\Bundle\CourseBundle\Model\Attendee\Query\FindAttendeeQuery;
use Sprungbrett\Component\Translation\Model\Localization;

class FindAttendeeQueryHandlerTest extends TestCase
{
    public function testHandle()
    {
        $repository = $this->prophesize(AttendeeRepositoryInterface::class);
        $handler = new FindAttendeeQueryHandler($repository->reveal());

        $localization = $this->prophesize(Localization::class);

        $attendee = $this->prophesize(AttendeeInterface::class);
        $repository->findOrCreateAttendeeById(42, $localization->reveal())->willReturn($attendee->reveal());

        $command = $this->prophesize(FindAttendeeQuery::class);
        $command->getId()->willReturn(42);
        $command->getLocalization()->willReturn($localization->reveal());

        $result = $handler->handle($command->reveal());
        $this->assertEquals($attendee->reveal(), $result);
    }
}
