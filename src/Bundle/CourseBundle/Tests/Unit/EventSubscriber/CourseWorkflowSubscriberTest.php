<?php

namespace Sprungbrett\Bundle\CourseBundle\Tests\Unit\EventSubscriber;

use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Sprungbrett\Bundle\CourseBundle\EventSubscriber\CourseWorkflowSubscriber;
use Sprungbrett\Bundle\CourseBundle\Model\Course\CourseInterface;
use Sulu\Bundle\RouteBundle\Manager\RouteManagerInterface;
use Sulu\Bundle\RouteBundle\Model\RoutableInterface;
use Sulu\Bundle\RouteBundle\Model\RouteInterface;
use Symfony\Component\Workflow\Event\Event;
use Symfony\Component\Workflow\Transition;

class CourseWorkflowSubscriberTest extends TestCase
{
    public function testCreateRouteOnEnteringPublishPlace()
    {
        $routeManager = $this->prophesize(RouteManagerInterface::class);

        $eventSubscriber = new CourseWorkflowSubscriber($routeManager->reveal());

        $course = $this->prophesize(RoutableInterface::class);
        $course->willImplement(CourseInterface::class);
        $course->getRoute()->willReturn(null);
        $transition = $this->prophesize(Transition::class);
        $transition->getTos()->willReturn([CourseInterface::WORKFLOW_STAGE_PUBLISHED]);

        $event = $this->prophesize(Event::class);
        $event->getSubject()->willReturn($course->reveal());
        $event->getTransition()->willReturn($transition->reveal());

        $routeManager->create($course->reveal())->shouldBeCalled();

        $eventSubscriber->createRouteOnEnteringPublishPlace($event->reveal());
    }

    public function testCreateRouteOnEnteringPublishPlaceUpdate()
    {
        $routeManager = $this->prophesize(RouteManagerInterface::class);

        $eventSubscriber = new CourseWorkflowSubscriber($routeManager->reveal());

        $route = $this->prophesize(RouteInterface::class);
        $course = $this->prophesize(RoutableInterface::class);
        $course->willImplement(CourseInterface::class);
        $course->getRoute()->willReturn($route->reveal());
        $transition = $this->prophesize(Transition::class);
        $transition->getTos()->willReturn([CourseInterface::WORKFLOW_STAGE_PUBLISHED]);

        $event = $this->prophesize(Event::class);
        $event->getSubject()->willReturn($course->reveal());
        $event->getTransition()->willReturn($transition->reveal());

        $routeManager->update($course->reveal())->shouldBeCalled();

        $eventSubscriber->createRouteOnEnteringPublishPlace($event->reveal());
    }

    public function testCreateRouteOnEnteringOtherPlace()
    {
        $routeManager = $this->prophesize(RouteManagerInterface::class);

        $eventSubscriber = new CourseWorkflowSubscriber($routeManager->reveal());

        $course = $this->prophesize(CourseInterface::class);
        $transition = $this->prophesize(Transition::class);
        $transition->getTos()->willReturn([CourseInterface::WORKFLOW_STAGE_TEST]);

        $event = $this->prophesize(Event::class);
        $event->getSubject()->willReturn($course->reveal());
        $event->getTransition()->willReturn($transition->reveal());

        $routeManager->create(Argument::any())->shouldNotBeCalled();
        $routeManager->update(Argument::any())->shouldNotBeCalled();

        $eventSubscriber->createRouteOnEnteringPublishPlace($event->reveal());
    }

    public function testRemoveRouteOnEnteringTestPlace()
    {
        $routeManager = $this->prophesize(RouteManagerInterface::class);

        $eventSubscriber = new CourseWorkflowSubscriber($routeManager->reveal());

        $route = $this->prophesize(RouteInterface::class);
        $course = $this->prophesize(RoutableInterface::class);
        $course->willImplement(CourseInterface::class);
        $course->getRoute()->willReturn($route->reveal());
        $transition = $this->prophesize(Transition::class);
        $transition->getTos()->willReturn([CourseInterface::WORKFLOW_STAGE_TEST]);

        $event = $this->prophesize(Event::class);
        $event->getSubject()->willReturn($course->reveal());
        $event->getTransition()->willReturn($transition->reveal());

        $routeManager->create(Argument::any())->shouldNotBeCalled();
        $routeManager->update(Argument::any())->shouldNotBeCalled();

        $course->removeRoute()->shouldBeCalled();

        $eventSubscriber->removeRouteOnEnteringTestPlace($event->reveal());
    }

    public function testRemoveRouteOnEnteringOtherPlace()
    {
        $routeManager = $this->prophesize(RouteManagerInterface::class);

        $eventSubscriber = new CourseWorkflowSubscriber($routeManager->reveal());

        $course = $this->prophesize(RoutableInterface::class);
        $course->willImplement(CourseInterface::class);
        $transition = $this->prophesize(Transition::class);
        $transition->getTos()->willReturn([CourseInterface::WORKFLOW_STAGE_PUBLISHED]);

        $event = $this->prophesize(Event::class);
        $event->getSubject()->willReturn($course->reveal());
        $event->getTransition()->willReturn($transition->reveal());

        $routeManager->create(Argument::any())->shouldNotBeCalled();
        $routeManager->update(Argument::any())->shouldNotBeCalled();

        $course->removeRoute()->shouldNotBeCalled();

        $eventSubscriber->removeRouteOnEnteringTestPlace($event->reveal());
    }
}
