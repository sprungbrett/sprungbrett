<?php

namespace Sprungbrett\Component\Course\Tests\Unit\Model;

use PHPUnit\Framework\TestCase;
use Sprungbrett\Component\Course\Model\CourseInterface;
use Sprungbrett\Component\Course\Model\CourseTranslation;
use Sprungbrett\Component\Translation\Model\Localization;
use Sprungbrett\Component\Uuid\Model\Uuid;

class CourseTranslationTest extends TestCase
{
    public function testGetUuid()
    {
        $localization = $this->prophesize(Localization::class);
        $uuid = $this->prophesize(Uuid::class);

        $translation = new CourseTranslation($localization->reveal(), $uuid->reveal());

        $this->assertEquals($uuid->reveal(), $translation->getUuid());
    }

    public function testGetUuidGeneration()
    {
        $localization = $this->prophesize(Localization::class);
        $translation = new CourseTranslation($localization->reveal());

        $this->assertInstanceOf(Uuid::class, $translation->getUuid());
    }

    public function testGetId()
    {
        $localization = $this->prophesize(Localization::class);
        $uuid = $this->prophesize(Uuid::class);
        $uuid->getId()->willReturn('123-123-123');

        $translation = new CourseTranslation($localization->reveal(), $uuid->reveal());

        $this->assertEquals('123-123-123', $translation->getId());
    }

    public function testGetIdGeneration()
    {
        $localization = $this->prophesize(Localization::class);
        $translation = new CourseTranslation($localization->reveal());

        $this->assertTrue(is_string($translation->getId()));
    }

    public function testGetLocalization()
    {
        $localization = $this->prophesize(Localization::class);

        $translation = new CourseTranslation($localization->reveal());

        $this->assertEquals($localization->reveal(), $translation->getLocalization());
    }

    public function testGetWorkflowStage()
    {
        $localization = $this->prophesize(Localization::class);

        $translation = new CourseTranslation($localization->reveal());

        $this->assertEquals(CourseInterface::WORKFLOW_STAGE_NEW, $translation->getWorkflowStage());
    }

    public function testSetWorkflowStage()
    {
        $localization = $this->prophesize(Localization::class);

        $translation = new CourseTranslation($localization->reveal());

        $translation->setWorkflowStage(CourseInterface::WORKFLOW_STAGE_PUBLISHED);
        $this->assertEquals(CourseInterface::WORKFLOW_STAGE_PUBLISHED, $translation->getWorkflowStage());
    }

    public function testGetLocale()
    {
        $localization = $this->prophesize(Localization::class);
        $localization->getLocale()->willReturn('de_de');

        $translation = new CourseTranslation($localization->reveal());

        $this->assertEquals('de_de', $translation->getLocale());
    }

    public function testGetTitle()
    {
        $localization = $this->prophesize(Localization::class);

        $translation = new CourseTranslation($localization->reveal());
        $translation->setTitle('Sprungbrett is awesome');

        $this->assertEquals('Sprungbrett is awesome', $translation->getTitle());
    }
}
