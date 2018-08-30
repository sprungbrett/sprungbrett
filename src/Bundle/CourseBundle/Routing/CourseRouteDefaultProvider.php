<?php

namespace Sprungbrett\Bundle\CourseBundle\Routing;

use League\Tactician\CommandBus;
use Sprungbrett\Bundle\CourseBundle\Model\Course\CourseInterface;
use Sprungbrett\Bundle\CourseBundle\Model\Course\Query\FindCourseQuery;
use Sprungbrett\Bundle\CourseBundle\Structure\CourseBridge;
use Sulu\Bundle\HttpCacheBundle\CacheLifetime\CacheLifetimeResolverInterface;
use Sulu\Bundle\RouteBundle\Routing\Defaults\RouteDefaultsProviderInterface;
use Sulu\Component\Content\Compat\StructureManagerInterface;
use Sulu\Component\Content\Metadata\Factory\StructureMetadataFactoryInterface;
use Sulu\Component\Content\Metadata\StructureMetadata;

class CourseRouteDefaultProvider implements RouteDefaultsProviderInterface
{
    /**
     * @var CommandBus
     */
    private $commandBus;

    /**
     * @var StructureMetadataFactoryInterface
     */
    private $structureMetadataFactory;

    /**
     * @var StructureManagerInterface
     */
    private $structureManager;

    /**
     * @var CacheLifetimeResolverInterface
     */
    private $cacheLifetimeResolver;

    public function __construct(
        CommandBus $commandBus,
        StructureMetadataFactoryInterface $structureMetadataFactory,
        StructureManagerInterface $structureManager,
        CacheLifetimeResolverInterface $cacheLifetimeResolver
    ) {
        $this->commandBus = $commandBus;
        $this->structureMetadataFactory = $structureMetadataFactory;
        $this->structureManager = $structureManager;
        $this->cacheLifetimeResolver = $cacheLifetimeResolver;
    }

    public function getByEntity($entityClass, $id, $locale, $object = null)
    {
        $object = $object ?: $this->findCourse($id, $locale);
        $metadata = $this->structureMetadataFactory->getStructureMetadata('course', $object->getStructureType());

        if (!$metadata) {
            throw new \RuntimeException(sprintf('Metadata %s not found for type course', $object->getSt));
        }

        /** @var CourseBridge $structure */
        $structure = $this->structureManager->wrapStructure('course', $metadata);
        $structure->setCourse($object);

        return [
            'object' => $object,
            'view' => $metadata->getView(),
            'structure' => $structure,
            '_controller' => $metadata->getController(),
            '_cacheLifetime' => $this->getCacheLifetime($metadata),
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

    private function getCacheLifetime(StructureMetadata $metadata)
    {
        $cacheLifetime = $metadata->getCacheLifetime();
        if (!$cacheLifetime) {
            return null;
        }

        return $this->cacheLifetimeResolver->resolve($cacheLifetime['type'], $cacheLifetime['value']);
    }
}
