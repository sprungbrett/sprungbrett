<?php

namespace Sprungbrett\Bundle\CourseBundle\Model\Course\Command;

use Sprungbrett\Bundle\CourseBundle\Model\Course\CourseInterface;
use Sprungbrett\Bundle\CourseBundle\Model\Course\Query\FindCourseQuery;
use Sprungbrett\Bundle\CourseBundle\Model\Course\Query\ListCourseQuery;
use Sprungbrett\Component\Resource\Model\Command\CommandFactoryInterface;
use Sprungbrett\Component\Resource\Model\Command\CreateCommandInterface;
use Sprungbrett\Component\Resource\Model\Command\FindQueryInterface;
use Sprungbrett\Component\Resource\Model\Command\ListQueryInterface;
use Sprungbrett\Component\Resource\Model\Command\ModifyCommandInterface;
use Sprungbrett\Component\Resource\Model\Command\RemoveCommandInterface;

class CourseCommandFactory implements CommandFactoryInterface
{
    public function createListQuery(
        string $entityClass,
        string $resourceKey,
        string $locale,
        string $route,
        array $parameters
    ): ListQueryInterface {
        return new ListCourseQuery($entityClass, $resourceKey, $locale, $route, $parameters);
    }

    public function createFindQuery(string $id, string $locale): FindQueryInterface
    {
        return new FindCourseQuery($id, $locale);
    }

    public function createCreateCommand(string $locale, array $payload): CreateCommandInterface
    {
        return new CreateCourseCommand($locale, $payload);
    }

    public function createModifyCommand(string $id, string $locale, array $payload): ModifyCommandInterface
    {
        return new ModifyCourseCommand($id, $locale, $payload);
    }

    public function createRemoveCommand(string $id): RemoveCommandInterface
    {
        return new RemoveCourseCommand($id);
    }

    public function getActionCommands(string $id, string $locale): array
    {
        return [
            CourseInterface::TRANSITION_PUBLISH => new PublishCourseCommand($id, $locale),
            CourseInterface::TRANSITION_UNPUBLISH => new UnpublishCourseCommand($id, $locale),
        ];
    }
}
