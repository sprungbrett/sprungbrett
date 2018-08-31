<?php

namespace Sprungbrett\Component\Resource\Model\Command;

interface CommandFactoryInterface
{
    public function createListQuery(
        string $entityClass,
        string $resourceKey,
        string $locale,
        string $route,
        array $parameters
    ): ListQueryInterface;

    public function createFindQuery(string $id, string $locale): FindQueryInterface;

    public function createCreateCommand(string $locale, array $payload): CreateCommandInterface;

    public function createModifyCommand(string $id, string $locale, array $payload): ModifyCommandInterface;

    public function createRemoveCommand(string $id): RemoveCommandInterface;

    /**
     * @return ActionCommandInterface[]
     */
    public function getActionCommands(string $id, string $locale): array;
}
