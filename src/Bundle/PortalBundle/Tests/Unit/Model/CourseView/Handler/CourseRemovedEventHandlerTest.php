<?php

declare(strict_types=1);

namespace Sprungbrett\Bundle\PortalBundle\Tests\Unit\Model\CourseView\Handler;

use PHPUnit\Framework\TestCase;
use Sprungbrett\Bundle\CourseBundle\Model\Course\Event\CourseRemovedEvent;
use Sprungbrett\Bundle\PortalBundle\Model\CourseView\CourseViewInterface;
use Sprungbrett\Bundle\PortalBundle\Model\CourseView\CourseViewRepositoryInterface;
use Sprungbrett\Bundle\PortalBundle\Model\CourseView\Handler\CourseRemovedEventHandler;

class CourseRemovedEventHandlerTest extends TestCase
{
    /**
     * @var CourseViewRepositoryInterface
     */
    private $courseViewRepository;

    /**
     * @var CourseRemovedEventHandler
     */
    private $handler;

    /**
     * @var CourseViewInterface
     */
    private $courseView;

    /**
     * @var CourseRemovedEvent
     */
    private $event;

    protected function setUp()
    {
        $this->courseViewRepository = $this->prophesize(CourseViewRepositoryInterface::class);

        $this->handler = new CourseRemovedEventHandler($this->courseViewRepository->reveal());

        $this->courseView = $this->prophesize(CourseViewInterface::class);

        $this->event = $this->prophesize(CourseRemovedEvent::class);
        $this->event->getUuid()->willReturn('123-123-123');
    }

    public function testInvoke(): void
    {
        $this->courseViewRepository->findAllById('123-123-123')->willReturn([$this->courseView->reveal()]);
        $this->courseViewRepository->remove($this->courseView->reveal())->shouldBeCalled();

        $this->handler->__invoke($this->event->reveal());
    }
}
