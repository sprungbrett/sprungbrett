<?php

namespace Sprungbrett\Component\Course\Tests\Unit\Model;

use PHPUnit\Framework\TestCase;
use Sprungbrett\Component\Course\Model\CourseInterface;
use Sprungbrett\Component\Course\Model\CourseTranslation;
use Sprungbrett\Component\Translation\Model\Localization;

class CourseTranslationTest extends TestCase
{
    public function testGetId()
    {
        $localization = $this->prophesize(Localization::class);

        $translation = new CourseTranslation('123-123-123', $localization->reveal());

        $this->assertEquals('123-123-123', $translation->getId());
    }

    public function testGetLocalization()
    {
        $localization = $this->prophesize(Localization::class);

        $translation = new CourseTranslation('123-123-123', $localization->reveal());

        $this->assertEquals($localization->reveal(), $translation->getLocalization());
    }

    public function testGetWorkflowStage()
    {
        $localization = $this->prophesize(Localization::class);

        $translation = new CourseTranslation('123-123-123', $localization->reveal());

        $this->assertEquals(CourseInterface::WORKFLOW_STAGE_NEW, $translation->getWorkflowStage());
    }

    public function testSetWorkflowStage()
    {
        $localization = $this->prophesize(Localization::class);

        $translation = new CourseTranslation('123-123-123', $localization->reveal());

        $translation->setWorkflowStage(CourseInterface::WORKFLOW_STAGE_PUBLISHED);
        $this->assertEquals(CourseInterface::WORKFLOW_STAGE_PUBLISHED, $translation->getWorkflowStage());
    }

    public function testGetLocale()
    {
        $localization = $this->prophesize(Localization::class);
        $localization->getLocale()->willReturn('de_de');

        $translation = new CourseTranslation('123-123-123', $localization->reveal());

        $this->assertEquals('de_de', $translation->getLocale());
    }

    public function testGetTitle()
    {
        $localization = $this->prophesize(Localization::class);

        $translation = new CourseTranslation('123-123-123', $localization->reveal());
        $translation->setTitle('Sprungbrett is awesome');

        $this->assertEquals('Sprungbrett is awesome', $translation->getTitle());
    }
}
