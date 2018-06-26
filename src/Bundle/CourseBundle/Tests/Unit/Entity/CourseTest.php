<?php

namespace Sprungbrett\Bundle\CourseBundle\Tests\Unit\Entity;

use PHPUnit\Framework\TestCase;
use Sprungbrett\Bundle\CourseBundle\Entity\Course;
use Sulu\Bundle\RouteBundle\Model\RouteInterface;

class CourseTest extends TestCase
{
    public function testGetRouteNull()
    {
        $course = new Course('123-123-123');

        $this->assertNull($course->getRoute());
    }

    public function testGetRoute()
    {
        $route = $this->prophesize(RouteInterface::class);

        $course = new Course('123-123-123');
        $course->setRoute($route->reveal());

        $this->assertEquals($route->reveal(), $course->getRoute());
    }

    public function testRemoveRoute()
    {
        $route = $this->prophesize(RouteInterface::class);

        $course = new Course('123-123-123');
        $course->setRoute($route->reveal());

        $this->assertEquals($route->reveal(), $course->getRoute());

        $course->removeRoute();
        $this->assertNull($course->getRoute());
    }

    public function testGetRoutePathNull()
    {
        $course = new Course('123-123-123');

        $this->assertNull($course->getRoutePath());
    }

    public function testGetRoutePath()
    {
        $route = $this->prophesize(RouteInterface::class);
        $route->getPath()->willReturn('/sprungbrett-is-awesome');

        $course = new Course('123-123-123');
        $course->setRoute($route->reveal());

        $this->assertEquals('/sprungbrett-is-awesome', $course->getRoutePath());
    }
}
