<?php

declare(strict_types=1);

namespace Sprungbrett\Bundle\CourseBundle\Tests\Unit\Model\Course\Event;

use PHPUnit\Framework\TestCase;
use Sprungbrett\Bundle\CourseBundle\Model\Course\CourseInterface;
use Sprungbrett\Bundle\CourseBundle\Model\Course\Event\CourseModifiedEvent;

class CourseModifiedEventTest extends TestCase
{
    /**
     * @var CourseInterface
     */
    private $course;

    /**
     * @var CourseModifiedEvent
     */
    private $event;

    protected function setUp()
    {
        $this->course = $this->prophesize(CourseInterface::class);

        $this->event = new CourseModifiedEvent($this->course->reveal());
    }

    public function testGetCourse(): void
    {
        $this->assertEquals($this->course->reveal(), $this->event->getCourse());
    }
}
