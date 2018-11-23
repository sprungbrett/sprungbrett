<?php

declare(strict_types=1);

namespace Sprungbrett\Bundle\CourseBundle\Tests\Unit\Model\Course\Event;

use PHPUnit\Framework\TestCase;
use Sprungbrett\Bundle\CourseBundle\Model\Course\CourseInterface;
use Sprungbrett\Bundle\CourseBundle\Model\Course\Event\CoursePublishedEvent;

class CoursePublishedEventTest extends TestCase
{
    /**
     * @var CourseInterface
     */
    private $course;

    /**
     * @var CoursePublishedEvent
     */
    private $event;

    protected function setUp()
    {
        $this->course = $this->prophesize(CourseInterface::class);

        $this->event = new CoursePublishedEvent('en', $this->course->reveal());
    }

    public function testGetLocale(): void
    {
        $this->assertEquals('en', $this->event->getLocale());
    }

    public function testGetUuid(): void
    {
        $this->course->getUuid()->willReturn('123-123-123');

        $this->assertEquals('123-123-123', $this->event->getUuid());
    }
}
