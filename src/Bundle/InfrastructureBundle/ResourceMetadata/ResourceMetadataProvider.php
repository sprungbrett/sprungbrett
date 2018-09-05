<?php

namespace Sprungbrett\Bundle\InfrastructureBundle\ResourceMetadata;

use Sulu\Bundle\AdminBundle\FormMetadata\FormMetadata;
use Sulu\Bundle\AdminBundle\FormMetadata\FormXmlLoader;
use Sulu\Bundle\AdminBundle\ResourceMetadata\ResourceMetadataInterface;
use Sulu\Bundle\AdminBundle\ResourceMetadata\ResourceMetadataMapper;
use Sulu\Bundle\AdminBundle\ResourceMetadata\ResourceMetadataProviderInterface;
use Sulu\Bundle\AdminBundle\ResourceMetadata\Type\Type;
use Sulu\Bundle\AdminBundle\ResourceMetadata\TypedResourceMetadata;
use Symfony\Component\HttpKernel\Config\FileLocator;

class ResourceMetadataProvider implements ResourceMetadataProviderInterface
{
    /**
     * @var array
     */
    private $resources;

    /**
     * @var ResourceMetadataMapper
     */
    private $resourceMetadataMapper;

    /**
     * @var FileLocator
     */
    private $fileLocator;

    /**
     * @var FormXmlLoader
     */
    private $formXmlLoader;

    public function __construct(
        array $resources,
        ResourceMetadataMapper $resourceMetadataMapper,
        FileLocator $fileLocator,
        FormXmlLoader $formXmlLoader
    ) {
        $this->resources = $resources;
        $this->resourceMetadataMapper = $resourceMetadataMapper;
        $this->fileLocator = $fileLocator;
        $this->formXmlLoader = $formXmlLoader;
    }

    public function getAllResourceMetadata(string $locale): array
    {
        $resourceMetadataArray = [];

        foreach (array_keys($this->resources) as $resourceKey) {
            $resourceMetadataArray[] = $this->getResourceMetadata($resourceKey, $locale);
        }

        return $resourceMetadataArray;
    }

    public function getResourceMetadata(string $resourceKey, string $locale): ?ResourceMetadataInterface
    {
        if (!array_key_exists($resourceKey, $this->resources)) {
            return null;
        }

        return $this->loadResourceMetadata($resourceKey, $locale);
    }

    private function loadResourceMetadata(string $resourceKey, string $locale): TypedResourceMetadata
    {
        $resource = $this->resources[$resourceKey];

        $resourceMetadata = new TypedResourceMetadata();
        $resourceMetadata->setKey($resourceKey);
        $resourceMetadata->setDatagrid($this->resourceMetadataMapper->mapDatagrid($resource['datagrid'], $locale));
        $resourceMetadata->setEndpoint($resource['endpoint']);

        foreach ($resource['workflow_stages'] as $key => $path) {
            $formFile = $this->fileLocator->locate($path);

            /** @var FormMetadata $formStructure */
            $formStructure = $this->formXmlLoader->load($formFile, $resourceKey);

            $type = new Type($key);
            $type->setTitle($key);
            $type->setForm($this->resourceMetadataMapper->mapForm($formStructure->getChildren(), $locale));
            $type->setSchema($this->resourceMetadataMapper->mapSchema($formStructure->getProperties()));

            $resourceMetadata->addType($type);
        }

        return $resourceMetadata;
    }
}
