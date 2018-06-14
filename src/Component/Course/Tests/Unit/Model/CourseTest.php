<?php

namespace Sprungbrett\Component\Course\Tests\Unit\Model;

use PHPUnit\Framework\TestCase;
use Sprungbrett\Component\Course\Model\Course;
use Sprungbrett\Component\Uuid\Model\Uuid;

class CourseTest extends TestCase
{
    public function testGetUuid()
    {
        $uuid = $this->prophesize(Uuid::class);
        $course = new Course($uuid->reveal());

        $this->assertEquals($uuid->reveal(), $course->getUuid());
    }

    public function testGetUuidGeneration()
    {
        $course = new Course();

        $this->assertInstanceOf(Uuid::class, $course->getUuid());
    }

    public function testGetId()
    {
        $uuid = $this->prophesize(Uuid::class);
        $uuid->getId()->willReturn('123-123-123');
        $course = new Course($uuid->reveal());

        $this->assertEquals('123-123-123', $course->getId());
    }

    public function testGetIdGeneration()
    {
        $course = new Course();

        $this->assertTrue(is_string($course->getId()));
    }

    public function testGetTitle()
    {
        $course = new Course();
        $course->setTitle('Sprungbrett is awesome');

        $this->assertEquals('Sprungbrett is awesome', $course->getTitle());
    }
}
