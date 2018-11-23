<?php

declare(strict_types=1);

namespace Sprungbrett\Bundle\CourseBundle\Tests\Unit\Model\Course;

use PHPUnit\Framework\TestCase;
use Sprungbrett\Bundle\CourseBundle\Model\Course\CourseInterface;
use Sprungbrett\Bundle\CourseBundle\Model\Course\CourseTranslation;
use Sprungbrett\Bundle\CourseBundle\Model\Course\CourseTranslationInterface;

class CourseTranslationTest extends TestCase
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
        $this->course = $this->prophesize(CourseInterface::class);
        $this->translation = new CourseTranslation($this->course->reveal(), 'en');
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
}
