<?php

namespace Sprungbrett\Component\Course\Tests\Unit\Model\Handler;

use PHPUnit\Framework\TestCase;
use Sprungbrett\Component\Course\Model\Command\ModifyCourseCommand;
use Sprungbrett\Component\Course\Model\Course;
use Sprungbrett\Component\Course\Model\CourseRepositoryInterface;
use Sprungbrett\Component\Course\Model\Handler\ModifyCourseHandler;
use Sprungbrett\Component\Uuid\Model\Uuid;

class ModifyCourseHandlerTest extends TestCase
{
    public function testHandle()
    {
        $repository = $this->prophesize(CourseRepositoryInterface::class);
        $handler = new ModifyCourseHandler($repository->reveal());

        $uuid = $this->prophesize(Uuid::class);

        $course = $this->prophesize(Course::class);
        $repository->findByUuid($uuid->reveal())->willReturn($course->reveal());
        $course->setTitle('Sprungbrett is awesome')->shouldBeCalled();

        $command = $this->prophesize(ModifyCourseCommand::class);
        $command->getUuid()->willReturn($uuid->reveal());
        $command->getTitle()->willReturn('Sprungbrett is awesome');

        $result = $handler->handle($command->reveal());
        $this->assertEquals($course->reveal(), $result);
    }
}
