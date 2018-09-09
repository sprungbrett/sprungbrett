<?php

namespace Sprungbrett\Bundle\CourseBundle\Tests\Unit\Model\Course;

use PHPUnit\Framework\TestCase;
use Sprungbrett\Bundle\CourseBundle\Model\Course\CourseInterface;
use Sprungbrett\Bundle\CourseBundle\Model\Course\CourseTranslation;
use Sprungbrett\Component\Content\Model\ContentInterface;
use Sprungbrett\Component\Translation\Model\Localization;
use Sulu\Bundle\RouteBundle\Model\RouteInterface;

class CourseTranslationTest extends TestCase
{
    public function testGetLocalization()
    {
        $localization = $this->prophesize(Localization::class);
        $content = $this->prophesize(ContentInterface::class);
        $course = $this->prophesize(CourseInterface::class);

        $translation = new CourseTranslation($course->reveal(), $localization->reveal(), $content->reveal());

        $this->assertEquals($localization->reveal(), $translation->getLocalization());
    }

    public function testGetLocale()
    {
        $localization = $this->prophesize(Localization::class);
        $localization->getLocale()->willReturn('de_de');
        $content = $this->prophesize(ContentInterface::class);
        $course = $this->prophesize(CourseInterface::class);

        $translation = new CourseTranslation($course->reveal(), $localization->reveal(), $content->reveal());

        $this->assertEquals('de_de', $translation->getLocale());
    }

    public function testGetName()
    {
        $localization = $this->prophesize(Localization::class);
        $content = $this->prophesize(ContentInterface::class);
        $course = $this->prophesize(CourseInterface::class);

        $translation = new CourseTranslation($course->reveal(), $localization->reveal(), $content->reveal());

        $this->assertEquals('', $translation->getName());
    }

    public function testSetName()
    {
        $localization = $this->prophesize(Localization::class);
        $content = $this->prophesize(ContentInterface::class);
        $course = $this->prophesize(CourseInterface::class);

        $translation = new CourseTranslation($course->reveal(), $localization->reveal(), $content->reveal());

        $this->assertEquals($translation, $translation->setName('Sprungbrett is awesome'));
        $this->assertEquals('Sprungbrett is awesome', $translation->getName());
    }

    public function testGetDescription()
    {
        $localization = $this->prophesize(Localization::class);
        $content = $this->prophesize(ContentInterface::class);
        $course = $this->prophesize(CourseInterface::class);

        $translation = new CourseTranslation($course->reveal(), $localization->reveal(), $content->reveal());

        $this->assertEquals('', $translation->getDescription());
    }

    public function testSetDescription()
    {
        $localization = $this->prophesize(Localization::class);
        $content = $this->prophesize(ContentInterface::class);
        $course = $this->prophesize(CourseInterface::class);

        $translation = new CourseTranslation($course->reveal(), $localization->reveal(), $content->reveal());

        $this->assertEquals($translation, $translation->setDescription('Sprungbrett is awesome'));
        $this->assertEquals('Sprungbrett is awesome', $translation->getDescription());
    }

    public function testGetRoute()
    {
        $localization = $this->prophesize(Localization::class);
        $content = $this->prophesize(ContentInterface::class);
        $course = $this->prophesize(CourseInterface::class);

        $translation = new CourseTranslation($course->reveal(), $localization->reveal(), $content->reveal());

        $this->assertNull($translation->getRoute());
    }

    public function testSetRoute()
    {
        $localization = $this->prophesize(Localization::class);
        $content = $this->prophesize(ContentInterface::class);
        $course = $this->prophesize(CourseInterface::class);

        $translation = new CourseTranslation($course->reveal(), $localization->reveal(), $content->reveal());

        $route = $this->prophesize(RouteInterface::class);
        $this->assertEquals($translation, $translation->setRoute($route->reveal()));

        $this->assertEquals($route->reveal(), $translation->getRoute());
    }

    public function testRemoveRoute()
    {
        $localization = $this->prophesize(Localization::class);
        $content = $this->prophesize(ContentInterface::class);
        $course = $this->prophesize(CourseInterface::class);

        $translation = new CourseTranslation($course->reveal(), $localization->reveal(), $content->reveal());

        $route = $this->prophesize(RouteInterface::class);
        $translation->setRoute($route->reveal());
        $this->assertEquals($route->reveal(), $translation->getRoute());

        $this->assertEquals($translation, $translation->removeRoute());
        $this->assertNull($translation->getRoute());
    }

    public function testGetRoutePath()
    {
        $localization = $this->prophesize(Localization::class);
        $content = $this->prophesize(ContentInterface::class);
        $course = $this->prophesize(CourseInterface::class);

        $translation = new CourseTranslation($course->reveal(), $localization->reveal(), $content->reveal());

        $route = $this->prophesize(RouteInterface::class);
        $route->getPath()->willReturn('/sprungbrett-is-awesome');
        $translation->setRoute($route->reveal());

        $this->assertEquals('/sprungbrett-is-awesome', $translation->getRoutePath());
    }

    public function testGetId()
    {
        $localization = $this->prophesize(Localization::class);
        $content = $this->prophesize(ContentInterface::class);
        $course = $this->prophesize(CourseInterface::class);
        $course->getId()->willReturn('123-123-123');

        $translation = new CourseTranslation($course->reveal(), $localization->reveal(), $content->reveal());

        $this->assertEquals('123-123-123', $translation->getId());
    }
}
