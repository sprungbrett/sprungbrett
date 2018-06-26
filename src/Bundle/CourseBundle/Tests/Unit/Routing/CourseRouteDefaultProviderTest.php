<?php

namespace Sprungbrett\Bundle\CourseBundle\Tests\Unit\Routing;

use League\Tactician\CommandBus;
use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Sprungbrett\Bundle\CourseBundle\Controller\WebsiteCourseController;
use Sprungbrett\Bundle\CourseBundle\Entity\Course;
use Sprungbrett\Bundle\CourseBundle\Routing\CourseRouteDefaultProvider;
use Sprungbrett\Component\Course\Model\Course as ComponentCourse;
use Sprungbrett\Component\Course\Model\CourseInterface;
use Sprungbrett\Component\Course\Model\Query\FindCourseQuery;

class CourseRouteDefaultProviderTest extends TestCase
{
    public function testGetByEntity()
    {
        $commandBus = $this->prophesize(CommandBus::class);
        $provider = new CourseRouteDefaultProvider($commandBus->reveal());

        $course = $this->prophesize(CourseInterface::class);
        $commandBus->handle(
            Argument::that(
                function (FindCourseQuery $query) {
                    return '123-123-123' === $query->getId()
                        && 'de' === $query->getLocalization()->getLocale();
                }
            )
        )->willReturn($course->reveal());

        $defaults = $provider->getByEntity(Course::class, '123-123-123', 'de');

        $this->assertEquals($course->reveal(), $defaults['object']);
        $this->assertEquals('@SprungbrettCourse/Course/index', $defaults['view']);
        $this->assertEquals(WebsiteCourseController::class . ':indexAction', $defaults['_controller']);
        $this->assertEquals(3600, $defaults['_cacheLifetime']);
    }

    public function testGetByEntityWithObject()
    {
        $commandBus = $this->prophesize(CommandBus::class);
        $provider = new CourseRouteDefaultProvider($commandBus->reveal());

        $course = $this->prophesize(CourseInterface::class);
        $commandBus->handle(Argument::any())->shouldNotBeCalled();

        $defaults = $provider->getByEntity(Course::class, '123-123-123', 'de', $course->reveal());

        $this->assertEquals($course->reveal(), $defaults['object']);
        $this->assertEquals('@SprungbrettCourse/Course/index', $defaults['view']);
        $this->assertEquals(WebsiteCourseController::class . ':indexAction', $defaults['_controller']);
        $this->assertEquals(3600, $defaults['_cacheLifetime']);
    }

    public function testIsPublished()
    {
        $commandBus = $this->prophesize(CommandBus::class);
        $provider = new CourseRouteDefaultProvider($commandBus->reveal());

        $course = $this->prophesize(CourseInterface::class);
        $course->getWorkflowStage()->willReturn(CourseInterface::WORKFLOW_STAGE_PUBLISHED);
        $commandBus->handle(
            Argument::that(
                function (FindCourseQuery $query) {
                    return '123-123-123' === $query->getId()
                        && 'de' === $query->getLocalization()->getLocale();
                }
            )
        )->willReturn($course->reveal());

        $this->assertTrue($provider->isPublished(Course::class, '123-123-123', 'de'));
    }

    public function testIsNotPublished()
    {
        $commandBus = $this->prophesize(CommandBus::class);
        $provider = new CourseRouteDefaultProvider($commandBus->reveal());

        $course = $this->prophesize(CourseInterface::class);
        $course->getWorkflowStage()->willReturn(CourseInterface::WORKFLOW_STAGE_TEST);
        $commandBus->handle(
            Argument::that(
                function (FindCourseQuery $query) {
                    return '123-123-123' === $query->getId()
                        && 'de' === $query->getLocalization()->getLocale();
                }
            )
        )->willReturn($course->reveal());

        $this->assertFalse($provider->isPublished(Course::class, '123-123-123', 'de'));
    }

    public function testSupports()
    {
        $commandBus = $this->prophesize(CommandBus::class);
        $provider = new CourseRouteDefaultProvider($commandBus->reveal());

        $commandBus->handle(Argument::any())->shouldNotBeCalled();

        $this->assertTrue($provider->supports(Course::class));
        $this->assertTrue($provider->supports(ComponentCourse::class));
    }

    public function testSupportsNot()
    {
        $commandBus = $this->prophesize(CommandBus::class);
        $provider = new CourseRouteDefaultProvider($commandBus->reveal());

        $commandBus->handle(Argument::any())->shouldNotBeCalled();

        $this->assertFalse($provider->supports(\stdClass::class));
    }
}
