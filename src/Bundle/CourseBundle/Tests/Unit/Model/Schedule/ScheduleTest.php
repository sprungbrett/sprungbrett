<?php

declare(strict_types=1);

namespace Sprungbrett\Bundle\CourseBundle\Tests\Unit\Model\Schedule;

use PHPUnit\Framework\TestCase;
use Sprungbrett\Bundle\ContentBundle\Stages;
use Sprungbrett\Bundle\CourseBundle\Model\Course\CourseInterface;
use Sprungbrett\Bundle\CourseBundle\Model\Schedule\Schedule;
use Sprungbrett\Bundle\CourseBundle\Model\Schedule\ScheduleInterface;
use Sprungbrett\Bundle\CourseBundle\Model\Schedule\ScheduleTranslationInterface;

class ScheduleTest extends TestCase
{
    /**
     * @var CourseInterface
     */
    private $course;

    /**
     * @var ScheduleInterface
     */
    private $schedule;

    /**
     * @var ScheduleTranslationInterface
     */
    private $translation;

    protected function setUp(): void
    {
        $this->translation = $this->prophesize(ScheduleTranslationInterface::class);
        $this->course = $this->prophesize(CourseInterface::class);
        $this->schedule = new Schedule(
            '123-123-123',
            $this->course->reveal(),
            Stages::LIVE,
            ['en' => $this->translation->reveal()]
        );
    }

    public function testGetUuid(): void
    {
        $this->assertEquals('123-123-123', $this->schedule->getUuid());
    }

    public function testGetStage(): void
    {
        $this->assertEquals(Stages::LIVE, $this->schedule->getStage());
    }

    public function testGetCourse(): void
    {
        $this->assertEquals($this->course->reveal(), $this->schedule->getCourse());
    }

    public function testGetMinimumParticipants(): void
    {
        $this->assertEquals(0, $this->schedule->getMinimumParticipants());
    }

    public function testSetMinimumParticipants(): void
    {
        $this->assertEquals($this->schedule, $this->schedule->setMinimumParticipants(5));
        $this->assertEquals(5, $this->schedule->getMinimumParticipants());
    }

    public function testGetMaximumParticipants(): void
    {
        $this->assertEquals(0, $this->schedule->getMaximumParticipants());
    }

    public function testSetMaximumParticipants(): void
    {
        $this->assertEquals($this->schedule, $this->schedule->setMaximumParticipants(5));
        $this->assertEquals(5, $this->schedule->getMaximumParticipants());
    }

    public function testGetPrice(): void
    {
        $this->assertEquals(0, $this->schedule->getPrice());
    }

    public function testSetPrice(): void
    {
        $this->assertEquals($this->schedule, $this->schedule->setPrice(5));
        $this->assertEquals(5, $this->schedule->getPrice());
    }

    public function testGetName(): void
    {
        $this->translation->getName()->willReturn('Sprungbrett');
        $this->schedule->setCurrentLocale('en');

        $this->assertEquals('Sprungbrett', $this->schedule->getName());
    }

    public function testGetNameWithLocale(): void
    {
        $this->translation->getName()->willReturn('Sprungbrett');

        $this->assertEquals('Sprungbrett', $this->schedule->getName('en'));
    }

    public function testSetName(): void
    {
        $this->translation->setName('Sprungbrett')->shouldBeCalled();
        $this->schedule->setCurrentLocale('en');

        $this->assertEquals($this->schedule, $this->schedule->setName('Sprungbrett'));
    }

    public function testSetNameWithLocale(): void
    {
        $this->translation->setName('Sprungbrett')->shouldBeCalled();

        $this->assertEquals($this->schedule, $this->schedule->setName('Sprungbrett', 'en'));
    }

    public function testGetDescription(): void
    {
        $this->translation->getDescription()->willReturn('Sprungbrett is awesome');
        $this->schedule->setCurrentLocale('en');

        $this->assertEquals('Sprungbrett is awesome', $this->schedule->getDescription());
    }

    public function testGetDescriptionWithLocale(): void
    {
        $this->translation->getDescription()->willReturn('Sprungbrett is awesome');

        $this->assertEquals('Sprungbrett is awesome', $this->schedule->getDescription('en'));
    }

    public function testSetDescription(): void
    {
        $this->translation->setDescription('Sprungbrett is awesome')->shouldBeCalled();
        $this->schedule->setCurrentLocale('en');

        $this->assertEquals($this->schedule, $this->schedule->setDescription('Sprungbrett is awesome'));
    }

    public function testSetDescriptionWithLocale(): void
    {
        $this->translation->setDescription('Sprungbrett is awesome')->shouldBeCalled();

        $this->assertEquals($this->schedule, $this->schedule->setDescription('Sprungbrett is awesome', 'en'));
    }

    public function testGetTranslation(): void
    {
        $this->schedule->setCurrentLocale('en');

        $this->assertEquals($this->translation->reveal(), $this->schedule->getTranslation());
    }

    public function testGetTranslationWithLocale(): void
    {
        $this->assertEquals($this->translation->reveal(), $this->schedule->getTranslation('en'));
    }

    public function testGetTranslationNew(): void
    {
        $translation = $this->schedule->getTranslation('de');

        $this->assertInstanceOf(ScheduleTranslationInterface::class, $translation);
        $this->assertEquals('de', $translation->getLocale());
    }

    public function testGetCurrentLocale(): void
    {
        $this->schedule->setCurrentLocale('en');

        $this->assertEquals('en', $this->schedule->getCurrentLocale());
    }
}
