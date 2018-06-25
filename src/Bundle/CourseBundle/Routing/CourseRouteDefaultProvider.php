<?php

namespace Sprungbrett\Bundle\CourseBundle\Routing;

use League\Tactician\CommandBus;
use Sprungbrett\Bundle\CourseBundle\Controller\WebsiteCourseController;
use Sprungbrett\Component\Course\Model\CourseInterface;
use Sprungbrett\Component\Course\Model\Query\FindCourseQuery;
use Sulu\Bundle\RouteBundle\Routing\Defaults\RouteDefaultsProviderInterface;

class CourseRouteDefaultProvider implements RouteDefaultsProviderInterface
{
    /**
     * @var CommandBus
     */
    private $commandBus;

    public function __construct(CommandBus $commandBus)
    {
        $this->commandBus = $commandBus;
    }

    public function getByEntity($entityClass, $id, $locale, $object = null)
    {
        return [
            'object' => $object ?: $this->findCourse($id, $locale),
            'view' => '@SprungbrettCourse/Course/index',
            '_controller' => sprintf('%s:indexAction', WebsiteCourseController::class),
            '_cacheLifetime' => 3600,
        ];
    }

    public function isPublished($entityClass, $id, $locale)
    {
        $course = $this->findCourse($id, $locale);

        return CourseInterface::WORKFLOW_STAGE_PUBLISHED === $course->getWorkflowStage();
    }

    public function supports($entityClass)
    {
        return is_subclass_of($entityClass, CourseInterface::class);
    }

    private function findCourse(string $id, string $locale): CourseInterface
    {
        return $this->commandBus->handle(new FindCourseQuery($id, $locale));
    }
}
