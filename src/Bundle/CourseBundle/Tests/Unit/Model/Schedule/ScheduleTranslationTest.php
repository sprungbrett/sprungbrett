<?php

declare(strict_types=1);

namespace Sprungbrett\Bundle\CourseBundle\Tests\Unit\Model\Schedule;

use PHPUnit\Framework\TestCase;
use Sprungbrett\Bundle\CourseBundle\Model\Schedule\ScheduleInterface;
use Sprungbrett\Bundle\CourseBundle\Model\Schedule\ScheduleTranslation;
use Sprungbrett\Bundle\CourseBundle\Model\Schedule\ScheduleTranslationInterface;

class ScheduleTranslationTest extends TestCase
{
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
        $this->schedule = $this->prophesize(ScheduleInterface::class);
        $this->translation = new ScheduleTranslation($this->schedule->reveal(), 'en');
    }

    public function testGetLocale(): void
    {
        $this->assertEquals('en', $this->translation->getLocale());
    }

    public function testGetName(): void
    {
        $this->assertEquals('', $this->translation->getName());
    }

    public function testSetName(): void
    {
        $this->assertEquals($this->translation, $this->translation->setName('Sprungbrett'));
        $this->assertEquals('Sprungbrett', $this->translation->getName());
    }

    public function testGetDescription(): void
    {
        $this->assertEquals('', $this->translation->getDescription());
    }

    public function testSetDescription(): void
    {
        $this->assertEquals($this->translation, $this->translation->setDescription('Sprungbrett is awesome'));
        $this->assertEquals('Sprungbrett is awesome', $this->translation->getDescription());
    }
}
