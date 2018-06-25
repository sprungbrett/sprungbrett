<?php

namespace Sprungbrett\Bundle\CourseBundle\EventSubscriber;

use Sprungbrett\Bundle\CourseBundle\Entity\Course;
use Sprungbrett\Component\Course\Model\CourseInterface;
use Sulu\Bundle\RouteBundle\Manager\RouteManagerInterface;
use Sulu\Bundle\RouteBundle\Model\RoutableInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Workflow\Event\Event;

class CourseWorkflowSubscriber implements EventSubscriberInterface
{
    const WORKFLOW_ENTER_EVENT = 'workflow.course.enter';

    public static function getSubscribedEvents()
    {
        return [
            self::WORKFLOW_ENTER_EVENT => [
                ['createRouteOnEnteringPublishPlace', 0],
                ['removeRouteOnEnteringTestPlace', 0],
            ],
        ];
    }

    /**
     * @var RouteManagerInterface
     */
    private $routeManager;

    public function __construct(RouteManagerInterface $routeManager)
    {
        $this->routeManager = $routeManager;
    }

    public function createRouteOnEnteringPublishPlace(Event $event): void
    {
        $subject = $event->getSubject();
        $transition = $event->getTransition();

        if (!$subject instanceof RoutableInterface ||
            !in_array(CourseInterface::WORKFLOW_STAGE_PUBLISHED, $transition->getTos())
        ) {
            return;
        }

        if (!$subject->getRoute()) {
            $this->routeManager->create($subject);

            return;
        }

        $this->routeManager->update($subject);
    }

    public function removeRouteOnEnteringTestPlace(Event $event)
    {
        $subject = $event->getSubject();
        $transition = $event->getTransition();

        if (!$subject instanceof RoutableInterface
            || !$subject instanceof Course
            || !in_array(CourseInterface::WORKFLOW_STAGE_TEST, $transition->getTos())
            || !$subject->getRoute()
        ) {
            return;
        }

        $subject->removeRoute();
    }
}
