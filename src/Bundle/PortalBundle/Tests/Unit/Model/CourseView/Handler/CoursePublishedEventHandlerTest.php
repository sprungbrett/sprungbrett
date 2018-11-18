<?php

declare(strict_types=1);

namespace Sprungbrett\Bundle\PortalBundle\Tests\Unit\Model\CourseView\Handler;

use PHPUnit\Framework\TestCase;
use Sprungbrett\Bundle\ContentBundle\Model\Content\ContentInterface;
use Sprungbrett\Bundle\ContentBundle\Model\Content\ContentRepositoryInterface;
use Sprungbrett\Bundle\ContentBundle\Stages;
use Sprungbrett\Bundle\CourseBundle\Model\CourseInterface;
use Sprungbrett\Bundle\CourseBundle\Model\CourseRepositoryInterface;
use Sprungbrett\Bundle\CourseBundle\Model\Event\CoursePublishedEvent;
use Sprungbrett\Bundle\PortalBundle\Model\CourseView\CourseViewInterface;
use Sprungbrett\Bundle\PortalBundle\Model\CourseView\CourseViewRepositoryInterface;
use Sprungbrett\Bundle\PortalBundle\Model\CourseView\Handler\CoursePublishedEventHandler;
use Sulu\Bundle\RouteBundle\Manager\RouteManagerInterface;
use Sulu\Bundle\RouteBundle\Model\RouteInterface;

class CoursePublishedEventHandlerTest extends TestCase
{
    /**
     * @var CourseViewRepositoryInterface
     */
    private $courseViewRepository;

    /**
     * @var CourseRepositoryInterface
     */
    private $courseRepository;

    /**
     * @var ContentRepositoryInterface
     */
    private $contentRepository;

    /**
     * @var RouteManagerInterface
     */
    private $routeManager;

    /**
     * @var CoursePublishedEventHandler
     */
    private $handler;

    /**
     * @var CourseViewInterface
     */
    private $courseView;

    /**
     * @var CourseInterface
     */
    private $course;

    /**
     * @var ContentInterface
     */
    private $content;

    /**
     * @var CoursePublishedEvent
     */
    private $event;

    protected function setUp()
    {
        $this->courseViewRepository = $this->prophesize(CourseViewRepositoryInterface::class);
        $this->courseRepository = $this->prophesize(CourseRepositoryInterface::class);
        $this->contentRepository = $this->prophesize(ContentRepositoryInterface::class);
        $this->routeManager = $this->prophesize(RouteManagerInterface::class);

        $this->handler = new CoursePublishedEventHandler(
            $this->courseViewRepository->reveal(),
            $this->courseRepository->reveal(),
            $this->contentRepository->reveal(),
            $this->routeManager->reveal()
        );

        $this->courseView = $this->prophesize(CourseViewInterface::class);
        $this->course = $this->prophesize(CourseInterface::class);
        $this->content = $this->prophesize(ContentInterface::class);

        $this->event = $this->prophesize(CoursePublishedEvent::class);
        $this->event->getUuid()->willReturn('123-123-123');
        $this->event->getLocale()->willReturn('en');
    }

    public function testInvoke(): void
    {
        $this->courseRepository->findById('123-123-123', Stages::LIVE, 'en')->willReturn($this->course->reveal());
        $this->contentRepository->findByResource('courses', '123-123-123', Stages::LIVE, 'en')
            ->willReturn($this->content->reveal());

        $this->courseViewRepository->findById('123-123-123', 'en')->willReturn($this->courseView->reveal());

        $this->courseView->setCourse($this->course->reveal())->shouldBeCalled();
        $this->courseView->setContent($this->content->reveal())->shouldBeCalled();

        $route = $this->prophesize(RouteInterface::class);
        $this->courseView->getRoute()->willReturn($route->reveal());

        $this->routeManager->create($this->courseView->reveal())->shouldNotBeCalled();
        $this->routeManager->update($this->courseView->reveal())->shouldBeCalled();

        $this->handler->__invoke($this->event->reveal());
    }

    public function testInvokeCreateRoute(): void
    {
        $this->courseRepository->findById('123-123-123', Stages::LIVE, 'en')->willReturn($this->course->reveal());
        $this->contentRepository->findByResource('courses', '123-123-123', Stages::LIVE, 'en')
            ->willReturn($this->content->reveal());

        $this->courseViewRepository->findById('123-123-123', 'en')->willReturn($this->courseView->reveal());

        $this->courseView->setCourse($this->course->reveal())->shouldBeCalled();
        $this->courseView->setContent($this->content->reveal())->shouldBeCalled();

        $this->courseView->getRoute()->willReturn(null);

        $this->routeManager->create($this->courseView->reveal())->shouldBeCalled();
        $this->routeManager->update($this->courseView->reveal())->shouldNotBeCalled();

        $this->handler->__invoke($this->event->reveal());
    }

    public function testInvokeCreateCourseView(): void
    {
        $this->courseRepository->findById('123-123-123', Stages::LIVE, 'en')->willReturn($this->course->reveal());
        $this->contentRepository->findByResource('courses', '123-123-123', Stages::LIVE, 'en')
            ->willReturn($this->content->reveal());

        $this->courseViewRepository->findById('123-123-123', 'en')->willReturn(null);
        $this->courseViewRepository->create('123-123-123', 'en')->willReturn($this->courseView->reveal());

        $this->courseView->setCourse($this->course->reveal())->shouldBeCalled();
        $this->courseView->setContent($this->content->reveal())->shouldBeCalled();

        $this->courseView->getRoute()->willReturn(null);

        $this->routeManager->create($this->courseView->reveal())->shouldBeCalled();
        $this->routeManager->update($this->courseView->reveal())->shouldNotBeCalled();

        $this->handler->__invoke($this->event->reveal());
    }
}
