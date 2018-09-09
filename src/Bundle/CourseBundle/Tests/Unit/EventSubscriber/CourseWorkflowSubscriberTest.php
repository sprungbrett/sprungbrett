<?php

namespace Sprungbrett\Bundle\CourseBundle\Tests\Unit\EventSubscriber;

use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Sprungbrett\Bundle\CourseBundle\EventSubscriber\CourseWorkflowSubscriber;
use Sprungbrett\Bundle\CourseBundle\Model\Course\Course;
use Sprungbrett\Bundle\CourseBundle\Model\Course\CourseInterface;
use Sprungbrett\Component\Translation\Model\Localization;
use Sulu\Bundle\RouteBundle\Manager\RouteManagerInterface;
use Sulu\Bundle\RouteBundle\Model\RouteInterface;
use Sulu\Component\Localization\Localization as SuluLocalization;
use Sulu\Component\Webspace\Manager\WebspaceManagerInterface;
use Symfony\Component\Workflow\Event\Event;
use Symfony\Component\Workflow\Transition;

class CourseWorkflowSubscriberTest extends TestCase
{
    public function testCreateRouteOnEnteringPublishPlace()
    {
        $routeManager = $this->prophesize(RouteManagerInterface::class);
        $webspaceManager = $this->prophesize(WebspaceManagerInterface::class);
        $webspaceManager->getAllLocalizations()->willReturn([new SuluLocalization('de'), new SuluLocalization('en')]);

        $eventSubscriber = new CourseWorkflowSubscriber($routeManager->reveal(), $webspaceManager->reveal());

        $course = new Course('123-123-123');
        $course->setCurrentLocalization(new Localization('de_at'));

        $transition = $this->prophesize(Transition::class);
        $transition->getTos()->willReturn([CourseInterface::WORKFLOW_STAGE_PUBLISHED]);

        $event = $this->prophesize(Event::class);
        $event->getSubject()->willReturn($course);
        $event->getTransition()->willReturn($transition->reveal());

        $routeManager->create(
            Argument::that(
                function (Course $course) {
                    return 'de' === $course->getLocale();
                }
            )
        )->shouldBeCalled();
        $routeManager->create(
            Argument::that(
                function (Course $course) {
                    return 'en' === $course->getLocale();
                }
            )
        )->shouldBeCalled();

        $eventSubscriber->createRouteOnEnteringPublishPlace($event->reveal());

        $this->assertEquals('de_at', $course->getLocale());
    }

    public function testCreateRouteOnEnteringPublishPlaceUpdate()
    {
        $routeManager = $this->prophesize(RouteManagerInterface::class);
        $webspaceManager = $this->prophesize(WebspaceManagerInterface::class);
        $webspaceManager->getAllLocalizations()->willReturn([new SuluLocalization('de'), new SuluLocalization('en')]);

        $eventSubscriber = new CourseWorkflowSubscriber($routeManager->reveal(), $webspaceManager->reveal());

        $route = $this->prophesize(RouteInterface::class);
        $course = new Course('123-123-123');
        $course->setCurrentLocalization(new Localization('en'));
        $course->setRoute($route->reveal());
        $course->setCurrentLocalization(new Localization('de'));
        $course->setRoute($route->reveal());
        $course->setCurrentLocalization(new Localization('de_at'));

        $transition = $this->prophesize(Transition::class);
        $transition->getTos()->willReturn([CourseInterface::WORKFLOW_STAGE_PUBLISHED]);

        $event = $this->prophesize(Event::class);
        $event->getSubject()->willReturn($course);
        $event->getTransition()->willReturn($transition->reveal());

        $routeManager->update(
            Argument::that(
                function (Course $course) {
                    return 'de' === $course->getLocale();
                }
            )
        )->shouldBeCalled();
        $routeManager->update(
            Argument::that(
                function (Course $course) {
                    return 'en' === $course->getLocale();
                }
            )
        )->shouldBeCalled();

        $eventSubscriber->createRouteOnEnteringPublishPlace($event->reveal());

        $this->assertEquals('de_at', $course->getLocale());
    }

    public function testCreateRouteOnEnteringOtherPlace()
    {
        $routeManager = $this->prophesize(RouteManagerInterface::class);
        $webspaceManager = $this->prophesize(WebspaceManagerInterface::class);
        $webspaceManager->getAllLocalizations()->willReturn([new SuluLocalization('de'), new SuluLocalization('en')]);

        $eventSubscriber = new CourseWorkflowSubscriber($routeManager->reveal(), $webspaceManager->reveal());

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
        $webspaceManager = $this->prophesize(WebspaceManagerInterface::class);
        $webspaceManager->getAllLocalizations()->willReturn([new SuluLocalization('de'), new SuluLocalization('en')]);

        $eventSubscriber = new CourseWorkflowSubscriber($routeManager->reveal(), $webspaceManager->reveal());

        $localization = $this->prophesize(Localization::class);

        $route = $this->prophesize(RouteInterface::class);
        $course = $this->prophesize(Course::class);
        $course->getRoute()->willReturn($route->reveal());
        $course->getLocalization()->willReturn($localization->reveal());
        $course->setCurrentLocalization(Argument::any())->shouldBeCalled();
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
        $webspaceManager = $this->prophesize(WebspaceManagerInterface::class);
        $webspaceManager->getAllLocalizations()->willReturn([new SuluLocalization('de'), new SuluLocalization('en')]);

        $eventSubscriber = new CourseWorkflowSubscriber($routeManager->reveal(), $webspaceManager->reveal());

        $course = $this->prophesize(Course::class);
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
