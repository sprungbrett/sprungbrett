<?php

declare(strict_types=1);

namespace Sprungbrett\Bundle\CourseBundle\Tests\Unit\Model;

use PHPUnit\Framework\TestCase;
use Sprungbrett\Bundle\ContentBundle\Stages;
use Sprungbrett\Bundle\CourseBundle\Model\Course;
use Sprungbrett\Bundle\CourseBundle\Model\CourseInterface;
use Sprungbrett\Bundle\CourseBundle\Model\CourseTranslationInterface;

class CourseTest extends TestCase
{
    /**
     * @var CourseInterface
     */
    private $course;

    /**
     * @var CourseTranslationInterface
     */
    private $translation;

    protected function setUp(): void
    {
        $this->translation = $this->prophesize(CourseTranslationInterface::class);
        $this->course = new Course('123-123-123', Stages::LIVE, ['en' => $this->translation->reveal()]);
    }

    public function testGetUuid(): void
    {
        $this->assertEquals('123-123-123', $this->course->getUuid());
    }

    public function testGetStage(): void
    {
        $this->assertEquals(Stages::LIVE, $this->course->getStage());
    }

    public function testGetName(): void
    {
        $this->translation->getName()->willReturn('Sprungbrett');
        $this->course->setCurrentLocale('en');

        $this->assertEquals('Sprungbrett', $this->course->getName());
    }

    public function testGetNameWithLocale(): void
    {
        $this->translation->getName()->willReturn('Sprungbrett');

        $this->assertEquals('Sprungbrett', $this->course->getName('en'));
    }

    public function testSetName(): void
    {
        $this->translation->setName('Sprungbrett')->shouldBeCalled();
        $this->course->setCurrentLocale('en');

        $this->assertEquals($this->course, $this->course->setName('Sprungbrett'));
    }

    public function testSetNameWithLocale(): void
    {
        $this->translation->setName('Sprungbrett')->shouldBeCalled();

        $this->assertEquals($this->course, $this->course->setName('Sprungbrett', 'en'));
    }

    public function testGetTranslation(): void
    {
        $this->course->setCurrentLocale('en');

        $this->assertEquals($this->translation->reveal(), $this->course->getTranslation());
    }

    public function testGetTranslationWithLocale(): void
    {
        $this->assertEquals($this->translation->reveal(), $this->course->getTranslation('en'));
    }

    public function testGetTranslationNew(): void
    {
        $translation = $this->course->getTranslation('de');

        $this->assertInstanceOf(CourseTranslationInterface::class, $translation);
        $this->assertEquals('de', $translation->getLocale());
    }
}
