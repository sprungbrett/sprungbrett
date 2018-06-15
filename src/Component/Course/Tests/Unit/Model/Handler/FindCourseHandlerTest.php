<?php

namespace Sprungbrett\Component\Course\Tests\Unit\Model\Handler;

use PHPUnit\Framework\TestCase;
use Sprungbrett\Component\Course\Model\Course;
use Sprungbrett\Component\Course\Model\CourseRepositoryInterface;
use Sprungbrett\Component\Course\Model\Handler\FindCourseHandler;
use Sprungbrett\Component\Course\Model\Query\FindCourseQuery;
use Sprungbrett\Component\Uuid\Model\Uuid;

class FindCourseHandlerTest extends TestCase
{
    public function testHandle()
    {
        $repository = $this->prophesize(CourseRepositoryInterface::class);
        $handler = new FindCourseHandler($repository->reveal());

        $uuid = $this->prophesize(Uuid::class);

        $course = $this->prophesize(Course::class);
        $repository->findByUuid($uuid->reveal())->willReturn($course->reveal());

        $command = $this->prophesize(FindCourseQuery::class);
        $command->getUuid()->willReturn($uuid->reveal());

        $result = $handler->handle($command->reveal());
        $this->assertEquals($course->reveal(), $result);
    }
}
