<?php

declare(strict_types=1);

namespace Sprungbrett\Bundle\CourseBundle\Tests\Unit\Model\Course\Event;

use PHPUnit\Framework\TestCase;
use Sprungbrett\Bundle\CourseBundle\Model\Course\Event\CourseRemovedEvent;

class CourseRemovedEventTest extends TestCase
{
    /**
     * @var CourseRemovedEvent
     */
    private $event;

    protected function setUp()
    {
        $this->event = new CourseRemovedEvent('123-123-123');
    }

    public function testGetUuid(): void
    {
        $this->assertEquals('123-123-123', $this->event->getUuid());
    }
}
