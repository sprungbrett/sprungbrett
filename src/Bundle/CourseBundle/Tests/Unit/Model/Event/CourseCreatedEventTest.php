<?php

declare(strict_types=1);

namespace Sprungbrett\Bundle\CourseBundle\Tests\Unit\Model\Event;

use PHPUnit\Framework\TestCase;
use Sprungbrett\Bundle\CourseBundle\Model\CourseInterface;
use Sprungbrett\Bundle\CourseBundle\Model\Event\CourseCreatedEvent;

class CourseCreatedEventTest extends TestCase
{
    /**
     * @var CourseInterface
     */
    private $course;

    /**
     * @var CourseCreatedEvent
     */
    private $event;

    protected function setUp()
    {
        $this->course = $this->prophesize(CourseInterface::class);

        $this->event = new CourseCreatedEvent($this->course->reveal());
    }

    public function testGetCourse(): void
    {
        $this->assertEquals($this->course->reveal(), $this->event->getCourse());
    }
}
