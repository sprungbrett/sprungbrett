<?php

declare(strict_types=1);

namespace Sprungbrett\Bundle\PortalBundle\Routing;

use Sprungbrett\Bundle\PortalBundle\Model\CourseView\CourseViewInterface;
use Sprungbrett\Bundle\PortalBundle\Model\CourseView\Exception\CourseViewNotFoundException;
use Sprungbrett\Bundle\PortalBundle\Model\CourseView\Query\FindCourseViewQuery;
use Sulu\Bundle\HttpCacheBundle\CacheLifetime\CacheLifetimeResolverInterface;
use Sulu\Bundle\RouteBundle\Routing\Defaults\RouteDefaultsProviderInterface;
use Sulu\Component\Content\Metadata\Factory\StructureMetadataFactoryInterface;
use Sulu\Component\Content\Metadata\StructureMetadata;
use Symfony\Component\Messenger\MessageBusInterface;

class CourseRouteDefaultsProvider implements RouteDefaultsProviderInterface
{
    /**
     * @var MessageBusInterface
     */
    private $messageBus;

    /**
     * @var StructureMetadataFactoryInterface
     */
    private $structureMetadataFactory;

    /**
     * @var CacheLifetimeResolverInterface
     */
    private $cacheLifetimeResolver;

    public function __construct(
        MessageBusInterface $messageBus,
        StructureMetadataFactoryInterface $structureMetadataFactory,
        CacheLifetimeResolverInterface $cacheLifetimeResolver
    ) {
        $this->messageBus = $messageBus;
        $this->structureMetadataFactory = $structureMetadataFactory;
        $this->cacheLifetimeResolver = $cacheLifetimeResolver;
    }

    public function getByEntity($entityClass, $id, $locale, $object = null)
    {
        if (!$object) {
            $object = $this->loadCourseView($id, $locale);
        }

        /** @var StructureMetadata $metadata */
        $metadata = $this->structureMetadataFactory->getStructureMetadata(
            'courses',
            $object->getContent()->getType()
        );

        return [
            'object' => $object,
            'view' => $metadata->getView(),
            '_cacheLifetime' => $this->getCacheLifetime($metadata),
            '_controller' => $metadata->getController(),
        ];
    }

    public function isPublished($entityClass, $id, $locale)
    {
        try {
            $this->loadCourseView($id, $locale);
        } catch (CourseViewNotFoundException $exception) {
            return false;
        }

        return true;
    }

    public function supports($entityClass)
    {
        return is_a($entityClass, CourseViewInterface::class, true);
    }

    protected function loadCourseView(string $id, string $locale): CourseViewInterface
    {
        return $this->messageBus->dispatch(new FindCourseViewQuery($id, $locale));
    }

    protected function getCacheLifetime(StructureMetadata $metadata): ?int
    {
        $cacheLifetime = $metadata->getCacheLifetime();
        if (!$cacheLifetime) {
            return null;
        }

        if (!is_array($cacheLifetime)
            || !isset($cacheLifetime['type'])
            || !isset($cacheLifetime['value'])
            || !$this->cacheLifetimeResolver->supports($cacheLifetime['type'], $cacheLifetime['value'])
        ) {
            throw new \InvalidArgumentException(
                sprintf('Invalid cachelifetime in course route default provider: %s', var_export($cacheLifetime, true))
            );
        }

        return $this->cacheLifetimeResolver->resolve($cacheLifetime['type'], $cacheLifetime['value']);
    }
}
