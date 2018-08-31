<?php

namespace Sprungbrett\Bundle\CourseBundle\Tests\Unit\Model\Course;

use PHPUnit\Framework\TestCase;
use Sprungbrett\Bundle\CourseBundle\Model\Course\Course;
use Sprungbrett\Bundle\CourseBundle\Model\Course\CourseTranslation;
use Sprungbrett\Component\Translation\Model\Localization;
use Sulu\Bundle\RouteBundle\Model\RouteInterface;

class CourseTest extends TestCase
{
    public function testGetId()
    {
        $course = new Course('123-123-123');

        $this->assertEquals('123-123-123', $course->getId());
    }

    public function testGetTrainerId()
    {
        $course = new Course('123-123-123');
        $course->setTrainerId(42);

        $this->assertEquals(42, $course->getTrainerId());
    }

    public function testGetTrainerIdNull()
    {
        $course = new Course('123-123-123');
        $course->setTrainerId(null);

        $this->assertNull($course->getTrainerId());
    }

    public function testGetLocalization()
    {
        $localization = $this->prophesize(Localization::class);

        $course = new Course('123-123-123');
        $course->setCurrentLocalization($localization->reveal());

        $this->assertEquals($localization->reveal(), $course->getLocalization());
    }

    public function getWorkflowStage()
    {
        $localization = $this->prophesize(Localization::class);
        $translationLocalization = $this->prophesize(Localization::class);

        $translation = $this->prophesize(CourseTranslation::class);
        $translation->getLocalization()->willReturn($translationLocalization->reveal());
        $translation->getWorkflowStage()->willReturn('published');

        $localization->equals($translationLocalization->reveal())->willReturn(true);

        $course = new Course('123-123-123', [$translation->reveal()]);
        $course->setCurrentLocalization($localization->reveal());

        $this->assertEquals('published', $course->getWorkflowStage());
    }

    public function getWorkflowStageWithLocalization()
    {
        $localization = $this->prophesize(Localization::class);
        $translationLocalization = $this->prophesize(Localization::class);

        $translation = $this->prophesize(CourseTranslation::class);
        $translation->getLocalization()->willReturn($translationLocalization->reveal());
        $translation->getWorkflowStage()->willReturn('published');

        $localization->equals($translationLocalization->reveal())->willReturn(true);

        $course = new Course('123-123-123', [$translation->reveal()]);

        $this->assertEquals('published', $course->getWorkflowStage($localization->reveal()));
    }

    public function testSetWorkflowStage()
    {
        $localization = $this->prophesize(Localization::class);
        $translationLocalization = $this->prophesize(Localization::class);

        $translation = $this->prophesize(CourseTranslation::class);
        $translation->getLocalization()->willReturn($translationLocalization->reveal());
        $translation->setWorkflowStage('published')->shouldBeCalled();

        $localization->equals($translationLocalization->reveal())->willReturn(true);

        $course = new Course('123-123-123', [$translation->reveal()]);
        $course->setCurrentLocalization($localization->reveal());

        $this->assertEquals($course, $course->setWorkflowStage('published'));
    }

    public function testSetWorkflowStageWithLocalization()
    {
        $localization = $this->prophesize(Localization::class);
        $translationLocalization = $this->prophesize(Localization::class);

        $translation = $this->prophesize(CourseTranslation::class);
        $translation->getLocalization()->willReturn($translationLocalization->reveal());
        $translation->setWorkflowStage('published')->shouldBeCalled();

        $localization->equals($translationLocalization->reveal())->willReturn(true);

        $course = new Course('123-123-123', [$translation->reveal()]);

        $this->assertEquals($course, $course->setWorkflowStage('published', $localization->reveal()));
    }

    public function testGetName()
    {
        $localization = $this->prophesize(Localization::class);
        $translationLocalization = $this->prophesize(Localization::class);

        $translation = $this->prophesize(CourseTranslation::class);
        $translation->getLocalization()->willReturn($translationLocalization->reveal());
        $translation->getName()->willReturn('Sprungbrett is awesome');

        $localization->equals($translationLocalization->reveal())->willReturn(true);

        $course = new Course('123-123-123', [$translation->reveal()]);
        $course->setCurrentLocalization($localization->reveal());

        $this->assertEquals('Sprungbrett is awesome', $course->getName());
    }

    public function testGetNameWithLocalization()
    {
        $localization = $this->prophesize(Localization::class);
        $translationLocalization = $this->prophesize(Localization::class);

        $translation = $this->prophesize(CourseTranslation::class);
        $translation->getLocalization()->willReturn($translationLocalization->reveal());
        $translation->getName()->willReturn('Sprungbrett is awesome');

        $localization->equals($translationLocalization->reveal())->willReturn(true);

        $course = new Course('123-123-123', [$translation->reveal()]);

        $this->assertEquals('Sprungbrett is awesome', $course->getName($localization->reveal()));
    }

    public function testSetTitle()
    {
        $localization = $this->prophesize(Localization::class);
        $translationLocalization = $this->prophesize(Localization::class);

        $translation = $this->prophesize(CourseTranslation::class);
        $translation->getLocalization()->willReturn($translationLocalization->reveal());
        $translation->setName('Sprungbrett is awesome')->shouldBeCalled();

        $localization->equals($translationLocalization->reveal())->willReturn(true);

        $course = new Course('123-123-123', [$translation->reveal()]);
        $course->setCurrentLocalization($localization->reveal());

        $this->assertEquals($course, $course->setName('Sprungbrett is awesome'));
    }

    public function testSetTitleWithLocalization()
    {
        $localization = $this->prophesize(Localization::class);
        $translationLocalization = $this->prophesize(Localization::class);

        $translation = $this->prophesize(CourseTranslation::class);
        $translation->getLocalization()->willReturn($translationLocalization->reveal());
        $translation->setName('Sprungbrett is awesome')->shouldBeCalled();

        $localization->equals($translationLocalization->reveal())->willReturn(true);

        $course = new Course('123-123-123', [$translation->reveal()]);

        $this->assertEquals($course, $course->setName('Sprungbrett is awesome', $localization->reveal()));
    }

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
