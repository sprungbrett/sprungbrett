<?php

declare(strict_types=1);

namespace Sprungbrett\Bundle\PortalBundle\Model\CourseView\Handler;

use Sprungbrett\Bundle\ContentBundle\Model\Content\ContentRepositoryInterface;
use Sprungbrett\Bundle\ContentBundle\Model\Content\Exception\ContentNotFoundException;
use Sprungbrett\Bundle\ContentBundle\Stages;
use Sprungbrett\Bundle\CourseBundle\Model\CourseRepositoryInterface;
use Sprungbrett\Bundle\CourseBundle\Model\Event\CoursePublishedEvent;
use Sprungbrett\Bundle\CourseBundle\Model\Exception\CourseNotFoundException;
use Sprungbrett\Bundle\PortalBundle\Model\CourseView\CourseViewRepositoryInterface;
use Sulu\Bundle\RouteBundle\Manager\RouteManagerInterface;

class CoursePublishedEventHandler
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

    public function __construct(
        CourseViewRepositoryInterface $courseViewRepository,
        CourseRepositoryInterface $courseRepository,
        ContentRepositoryInterface $contentRepository,
        RouteManagerInterface $routeManager
    ) {
        $this->courseViewRepository = $courseViewRepository;
        $this->courseRepository = $courseRepository;
        $this->contentRepository = $contentRepository;
        $this->routeManager = $routeManager;
    }

    public function __invoke(CoursePublishedEvent $event): void
    {
        $course = $this->courseRepository->findById($event->getUuid(), Stages::LIVE, $event->getLocale());
        if (!$course) {
            throw new CourseNotFoundException($event->getUuid());
        }

        $content = $this->contentRepository->findByResource(
            'courses',
            $event->getUuid(),
            $event->getLocale(),
            Stages::LIVE
        );
        if (!$content) {
            throw new ContentNotFoundException('courses', $event->getUuid());
        }

        $courseView = $this->courseViewRepository->findById($event->getUuid(), $event->getLocale());
        if (!$courseView) {
            $courseView = $this->courseViewRepository->create($event->getUuid(), $event->getLocale());
        }

        $courseView->setCourse($course);
        $courseView->setContent($content);

        if (!$courseView->getRoute()) {
            $this->routeManager->create($courseView);

            return;
        }

        $this->routeManager->update($courseView);
    }
}
