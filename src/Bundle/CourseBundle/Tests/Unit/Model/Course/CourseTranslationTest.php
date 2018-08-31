<?php

namespace Sprungbrett\Bundle\CourseBundle\Tests\Unit\Model\Course;

use PHPUnit\Framework\TestCase;
use Sprungbrett\Bundle\CourseBundle\Model\Course\CourseInterface;
use Sprungbrett\Bundle\CourseBundle\Model\Course\CourseTranslation;
use Sprungbrett\Component\Content\Model\ContentInterface;
use Sprungbrett\Component\Translation\Model\Localization;

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

    public function testGetWorkflowStage()
    {
        $localization = $this->prophesize(Localization::class);
        $content = $this->prophesize(ContentInterface::class);
        $course = $this->prophesize(CourseInterface::class);

        $translation = new CourseTranslation($course->reveal(), $localization->reveal(), $content->reveal());

        $this->assertEquals(CourseInterface::WORKFLOW_STAGE_NEW, $translation->getWorkflowStage());
    }

    public function testSetWorkflowStage()
    {
        $localization = $this->prophesize(Localization::class);
        $content = $this->prophesize(ContentInterface::class);
        $course = $this->prophesize(CourseInterface::class);

        $translation = new CourseTranslation($course->reveal(), $localization->reveal(), $content->reveal());

        $translation->setWorkflowStage(CourseInterface::WORKFLOW_STAGE_PUBLISHED);
        $this->assertEquals(CourseInterface::WORKFLOW_STAGE_PUBLISHED, $translation->getWorkflowStage());
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
        $translation->setName('Sprungbrett is awesome');

        $this->assertEquals('Sprungbrett is awesome', $translation->getName());
    }
}
