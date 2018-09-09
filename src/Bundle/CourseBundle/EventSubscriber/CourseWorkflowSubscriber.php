<?php

namespace Sprungbrett\Bundle\CourseBundle\EventSubscriber;

use Sprungbrett\Bundle\CourseBundle\Model\Course\CourseInterface;
use Sprungbrett\Component\Translation\Model\Localization;
use Sulu\Bundle\RouteBundle\Manager\RouteManagerInterface;
use Sulu\Bundle\RouteBundle\Model\RoutableInterface;
use Sulu\Component\Localization\Localization as SuluLocalization;
use Sulu\Component\Webspace\Manager\WebspaceManagerInterface;
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

    /**
     * @var WebspaceManagerInterface
     */
    private $webspaceManager;

    public function __construct(RouteManagerInterface $routeManager, WebspaceManagerInterface $webspaceManager)
    {
        $this->routeManager = $routeManager;
        $this->webspaceManager = $webspaceManager;
    }

    public function createRouteOnEnteringPublishPlace(Event $event): void
    {
        $subject = $event->getSubject();
        $transition = $event->getTransition();

        if (!$subject instanceof RoutableInterface
            || !$subject instanceof CourseInterface
            || !in_array(CourseInterface::WORKFLOW_STAGE_PUBLISHED, $transition->getTos())
        ) {
            return;
        }

        $originalLocalization = $subject->getLocalization();
        foreach ($this->getLocalizations() as $localization) {
            $subject->setCurrentLocalization($localization);

            $this->createOrUpdateRoute($subject);
        }

        if ($originalLocalization) {
            $subject->setCurrentLocalization($originalLocalization);
        }
    }

    protected function createOrUpdateRoute(RoutableInterface $course): void
    {
        if (!$course->getRoute()) {
            $this->routeManager->create($course);

            return;
        }

        $this->routeManager->update($course);
    }

    public function removeRouteOnEnteringTestPlace(Event $event)
    {
        $subject = $event->getSubject();
        $transition = $event->getTransition();

        if (!$subject instanceof RoutableInterface
            || !$subject instanceof CourseInterface
            || !in_array(CourseInterface::WORKFLOW_STAGE_TEST, $transition->getTos())
            || !$subject->getRoute()
        ) {
            return;
        }

        $originalLocalization = $subject->getLocalization();
        foreach ($this->getLocalizations() as $localization) {
            $subject->setCurrentLocalization($localization);

            $subject->removeRoute();
        }

        if ($originalLocalization) {
            $subject->setCurrentLocalization($originalLocalization);
        }
    }

    /**
     * @return Localization[]
     */
    protected function getLocalizations(): array
    {
        // FIXME remove as soon as https://github.com/sulu/sulu/issues/3922 is fixed
        return array_values(
            array_map(
                function (SuluLocalization $localization) {
                    return new Localization($localization->getLocale());
                },
                $this->webspaceManager->getAllLocalizations()
            )
        );
    }
}
