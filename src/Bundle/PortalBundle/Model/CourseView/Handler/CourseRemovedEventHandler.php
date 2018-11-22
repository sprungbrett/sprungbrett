<?php

declare(strict_types=1);

namespace Sprungbrett\Bundle\PortalBundle\Model\CourseView\Handler;

use Sprungbrett\Bundle\CourseBundle\Model\Course\Event\CourseRemovedEvent;
use Sprungbrett\Bundle\PortalBundle\Model\CourseView\CourseViewRepositoryInterface;

class CourseRemovedEventHandler
{
    /**
     * @var CourseViewRepositoryInterface
     */
    private $courseViewRepository;

    public function __construct(CourseViewRepositoryInterface $courseViewRepository)
    {
        $this->courseViewRepository = $courseViewRepository;
    }

    public function __invoke(CourseRemovedEvent $event): void
    {
        $courseViews = $this->courseViewRepository->findAllById($event->getUuid());

        foreach ($courseViews as $courseView) {
            $this->courseViewRepository->remove($courseView);
        }
    }
}
