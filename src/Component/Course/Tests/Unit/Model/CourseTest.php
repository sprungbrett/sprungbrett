<?php

namespace Sprungbrett\Component\Course\Tests\Unit\Model;

use Doctrine\Common\Collections\ArrayCollection;
use PHPUnit\Framework\TestCase;
use Sprungbrett\Component\Course\Model\Course;
use Sprungbrett\Component\Course\Model\CourseTranslation;
use Sprungbrett\Component\Translation\Model\Localization;
use Sprungbrett\Component\Uuid\Model\Uuid;

class CourseTest extends TestCase
{
    public function testGetUuid()
    {
        $uuid = $this->prophesize(Uuid::class);
        $course = new Course(null, $uuid->reveal());

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
        $course = new Course(null, $uuid->reveal());

        $this->assertEquals('123-123-123', $course->getId());
    }

    public function testGetIdGeneration()
    {
        $course = new Course();

        $this->assertTrue(is_string($course->getId()));
    }

    public function getWorkflowStage()
    {
        $localization = $this->prophesize(Localization::class);
        $translationLocalization = $this->prophesize(Localization::class);

        $translation = $this->prophesize(CourseTranslation::class);
        $translation->getLocalization()->willReturn($translationLocalization->reveal());
        $translation->getWorkflowStage()->willReturn('published');

        $localization->equals($translationLocalization->reveal())->willReturn(true);

        $course = new Course(new ArrayCollection([$translation->reveal()]));
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

        $course = new Course(new ArrayCollection([$translation->reveal()]));

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

        $course = new Course(new ArrayCollection([$translation->reveal()]));
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

        $course = new Course(new ArrayCollection([$translation->reveal()]));

        $this->assertEquals($course, $course->setWorkflowStage('published', $localization->reveal()));
    }

    public function testGetTitle()
    {
        $localization = $this->prophesize(Localization::class);
        $translationLocalization = $this->prophesize(Localization::class);

        $translation = $this->prophesize(CourseTranslation::class);
        $translation->getLocalization()->willReturn($translationLocalization->reveal());
        $translation->getTitle()->willReturn('Sprungbrett is awesome');

        $localization->equals($translationLocalization->reveal())->willReturn(true);

        $course = new Course(new ArrayCollection([$translation->reveal()]));
        $course->setCurrentLocalization($localization->reveal());

        $this->assertEquals('Sprungbrett is awesome', $course->getTitle());
    }

    public function testGetTitleWithLocalization()
    {
        $localization = $this->prophesize(Localization::class);
        $translationLocalization = $this->prophesize(Localization::class);

        $translation = $this->prophesize(CourseTranslation::class);
        $translation->getLocalization()->willReturn($translationLocalization->reveal());
        $translation->getTitle()->willReturn('Sprungbrett is awesome');

        $localization->equals($translationLocalization->reveal())->willReturn(true);

        $course = new Course(new ArrayCollection([$translation->reveal()]));

        $this->assertEquals('Sprungbrett is awesome', $course->getTitle($localization->reveal()));
    }

    public function testSetTitle()
    {
        $localization = $this->prophesize(Localization::class);
        $translationLocalization = $this->prophesize(Localization::class);

        $translation = $this->prophesize(CourseTranslation::class);
        $translation->getLocalization()->willReturn($translationLocalization->reveal());
        $translation->setTitle('Sprungbrett is awesome')->shouldBeCalled();

        $localization->equals($translationLocalization->reveal())->willReturn(true);

        $course = new Course(new ArrayCollection([$translation->reveal()]));
        $course->setCurrentLocalization($localization->reveal());

        $this->assertEquals($course, $course->setTitle('Sprungbrett is awesome'));
    }

    public function testSetTitleWithLocalization()
    {
        $localization = $this->prophesize(Localization::class);
        $translationLocalization = $this->prophesize(Localization::class);

        $translation = $this->prophesize(CourseTranslation::class);
        $translation->getLocalization()->willReturn($translationLocalization->reveal());
        $translation->setTitle('Sprungbrett is awesome')->shouldBeCalled();

        $localization->equals($translationLocalization->reveal())->willReturn(true);

        $course = new Course(new ArrayCollection([$translation->reveal()]));

        $this->assertEquals($course, $course->setTitle('Sprungbrett is awesome', $localization->reveal()));
    }
}
