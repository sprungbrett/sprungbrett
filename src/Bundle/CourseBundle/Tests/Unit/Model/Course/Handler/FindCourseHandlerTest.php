<?php

namespace Sprungbrett\Bundle\CourseBundle\Tests\Unit\Model\Course\Handler;

use PHPUnit\Framework\TestCase;
use Sprungbrett\Bundle\CourseBundle\Model\Course\CourseInterface;
use Sprungbrett\Bundle\CourseBundle\Model\Course\CourseRepositoryInterface;
use Sprungbrett\Bundle\CourseBundle\Model\Course\Handler\FindCourseHandler;
use Sprungbrett\Bundle\CourseBundle\Model\Course\Query\FindCourseQuery;
use Sprungbrett\Component\Translation\Model\Localization;

class FindCourseHandlerTest extends TestCase
{
    public function testHandle()
    {
        $repository = $this->prophesize(CourseRepositoryInterface::class);
        $handler = new FindCourseHandler($repository->reveal());

        $localization = $this->prophesize(Localization::class);

        $course = $this->prophesize(CourseInterface::class);
        $repository->findById('123-123-123', $localization->reveal())->willReturn($course->reveal());

        $command = $this->prophesize(FindCourseQuery::class);
        $command->getId()->willReturn('123-123-123');
        $command->getLocalization()->willReturn($localization->reveal());

        $result = $handler->handle($command->reveal());
        $this->assertEquals($course->reveal(), $result);
    }
}
