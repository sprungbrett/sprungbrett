<?php

namespace Sprungbrett\Bundle\InfrastructureBundle\Content\Resolver;

use Sprungbrett\Component\Content\Model\ContentableInterface;
use Sprungbrett\Component\Content\Resolver\PropertyResolverInterface;
use Sulu\Component\Content\Compat\StructureManagerInterface;
use Sulu\Component\Content\ContentTypeManagerInterface;
use Sulu\Component\Content\Metadata\Factory\StructureMetadataFactoryInterface;
use Sulu\Component\Content\Metadata\PropertyMetadata;
use Sulu\Component\Webspace\Analyzer\Attributes\RequestAttributes;
use Symfony\Component\HttpFoundation\RequestStack;

class PropertyResolver implements PropertyResolverInterface
{
    /**
     * @var StructureMetadataFactoryInterface
     */
    private $metadataFactory;

    /**
     * @var ContentTypeManagerInterface
     */
    private $contentTypeManager;

    /**
     * @var StructureManagerInterface
     */
    private $structureManager;

    /**
     * @var RequestStack
     */
    private $requestStack;

    public function __construct(
        StructureMetadataFactoryInterface $metadataFactory,
        ContentTypeManagerInterface $contentTypeManager,
        StructureManagerInterface $structureManager,
        RequestStack $requestStack
    ) {
        $this->metadataFactory = $metadataFactory;
        $this->contentTypeManager = $contentTypeManager;
        $this->structureManager = $structureManager;
        $this->requestStack = $requestStack;
    }

    public function getAvailableProperties(ContentableInterface $object): array
    {
        $metadata = $this->metadataFactory->getStructureMetadata('course', $object->getStructureType());
        if (!$metadata) {
            return [];
        }

        return array_map(
            function (PropertyMetadata $propertyMetadata) {
                return $propertyMetadata->getName();
            },
            $metadata->getProperties()
        );
    }

    /**
     * @param mixed $value
     *
     * @return mixed
     */
    public function resolveContentData(ContentableInterface $object, string $propertyName, $value)
    {
        $metadata = $this->metadataFactory->getStructureMetadata('course', $object->getStructureType());
        if (!$metadata) {
            return null;
        }

        $propertyMetadata = $metadata->getProperty($propertyName);

        $contentType = $this->contentTypeManager->get($propertyMetadata->getType());
        if (null === $value) {
            return $contentType->getDefaultValue();
        }

        $structure = $this->structureManager->wrapStructure('course', $metadata);
        if (!$structure) {
            return null;
        }

        $structure->setWebspaceKey($this->getWebspaceKey() ?: '');
        $property = $structure->getProperty($propertyName);
        $property->setValue($value);

        return $contentType->getContentData($property);
    }

    /**
     * @param mixed $value
     *
     * @return mixed
     */
    public function resolveViewData(ContentableInterface $object, string $propertyName, $value)
    {
        $metadata = $this->metadataFactory->getStructureMetadata('course', $object->getStructureType());
        if (!$metadata) {
            return null;
        }

        $propertyMetadata = $metadata->getProperty($propertyName);

        $contentType = $this->contentTypeManager->get($propertyMetadata->getType());
        if (null === $value) {
            return [];
        }

        $structure = $this->structureManager->wrapStructure('course', $metadata);
        if (!$structure) {
            return null;
        }

        $structure->setWebspaceKey($this->getWebspaceKey() ?: '');
        $property = $structure->getProperty($propertyName);
        $property->setValue($value);

        return $contentType->getViewData($property);
    }

    private function getWebspaceKey(): ?string
    {
        $request = $this->requestStack->getCurrentRequest();
        if (!$request) {
            return null;
        }

        /** @var RequestAttributes $attributes */
        $attributes = $request->attributes->get('_sulu');
        if (!$attributes) {
            return null;
        }

        return $attributes->getAttribute('webspaceKey');
    }
}
