<?php

namespace Sprungbrett\Bundle\InfrastructureBundle\Tests\Unit\Content\Resolver;

use PHPUnit\Framework\TestCase;
use Sprungbrett\Bundle\InfrastructureBundle\Content\Resolver\PropertyResolver;
use Sprungbrett\Component\Content\Model\ContentableInterface;
use Sulu\Component\Content\Compat\PropertyInterface;
use Sulu\Component\Content\Compat\StructureInterface;
use Sulu\Component\Content\Compat\StructureManagerInterface;
use Sulu\Component\Content\ContentTypeInterface;
use Sulu\Component\Content\ContentTypeManagerInterface;
use Sulu\Component\Content\Metadata\Factory\StructureMetadataFactoryInterface;
use Sulu\Component\Content\Metadata\PropertyMetadata;
use Sulu\Component\Content\Metadata\StructureMetadata;
use Sulu\Component\Webspace\Analyzer\Attributes\RequestAttributes;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

class PropertyResolverTest extends TestCase
{
    public function testGetAvailableProperties()
    {
        $metadataFactory = $this->prophesize(StructureMetadataFactoryInterface::class);
        $contentTypeManager = $this->prophesize(ContentTypeManagerInterface::class);
        $structureManager = $this->prophesize(StructureManagerInterface::class);
        $requestStack = $this->prophesize(RequestStack::class);

        $resolver = new PropertyResolver(
            $metadataFactory->reveal(),
            $contentTypeManager->reveal(),
            $structureManager->reveal(),
            $requestStack->reveal()
        );

        $object = $this->prophesize(ContentableInterface::class);
        $object->getStructureType()->willReturn('default');

        $metadata = $this->prophesize(StructureMetadata::class);
        $metadataFactory->getStructureMetadata('course', 'default')->willReturn($metadata->reveal());

        $propertyMetadata = $this->prophesize(PropertyMetadata::class);
        $propertyMetadata->getName()->willReturn('title');
        $metadata->getProperties()->willReturn([$propertyMetadata->reveal()]);

        $this->assertEquals(['title'], $resolver->getAvailableProperties($object->reveal()));
    }

    public function testResolveViewData()
    {
        $metadataFactory = $this->prophesize(StructureMetadataFactoryInterface::class);
        $contentTypeManager = $this->prophesize(ContentTypeManagerInterface::class);
        $structureManager = $this->prophesize(StructureManagerInterface::class);
        $requestStack = $this->prophesize(RequestStack::class);

        $resolver = new PropertyResolver(
            $metadataFactory->reveal(),
            $contentTypeManager->reveal(),
            $structureManager->reveal(),
            $requestStack->reveal()
        );

        $object = $this->prophesize(ContentableInterface::class);
        $object->getStructureType()->willReturn('default');

        $metadata = $this->prophesize(StructureMetadata::class);
        $metadataFactory->getStructureMetadata('course', 'default')->willReturn($metadata->reveal());

        $propertyMetadata = $this->prophesize(PropertyMetadata::class);
        $propertyMetadata->getType()->willReturn('text_line');
        $metadata->getProperty('title')->willReturn($propertyMetadata->reveal());

        $structure = $this->prophesize(StructureInterface::class);
        $structure->setWebspaceKey('sulu_io')->shouldBeCalled();
        $property = $this->prophesize(PropertyInterface::class);
        $property->setValue('Sprungbrett is awesome')->shouldBeCalled();
        $structure->getProperty('title')->willReturn($property->reveal());
        $structureManager->wrapStructure('course', $metadata->reveal())->willReturn($structure->reveal());

        $requestAttributes = $this->prophesize(RequestAttributes::class);
        $requestAttributes->getAttribute('webspaceKey')->willReturn('sulu_io');
        $request = new Request([], [], ['_sulu' => $requestAttributes->reveal()]);
        $requestStack->getCurrentRequest()->willReturn($request);

        $contentType = $this->prophesize(ContentTypeInterface::class);
        $contentTypeManager->get('text_line')->willReturn($contentType->reveal());
        $contentType->getViewData($property->reveal())->willReturn('Sprungbrett is awesome');

        $this->assertEquals(
            'Sprungbrett is awesome',
            $resolver->resolveViewData($object->reveal(), 'title', 'Sprungbrett is awesome')
        );
    }

    public function testResolveViewDataNull()
    {
        $metadataFactory = $this->prophesize(StructureMetadataFactoryInterface::class);
        $contentTypeManager = $this->prophesize(ContentTypeManagerInterface::class);
        $structureManager = $this->prophesize(StructureManagerInterface::class);
        $requestStack = $this->prophesize(RequestStack::class);

        $resolver = new PropertyResolver(
            $metadataFactory->reveal(),
            $contentTypeManager->reveal(),
            $structureManager->reveal(),
            $requestStack->reveal()
        );

        $object = $this->prophesize(ContentableInterface::class);
        $object->getStructureType()->willReturn('default');

        $metadata = $this->prophesize(StructureMetadata::class);
        $metadataFactory->getStructureMetadata('course', 'default')->willReturn($metadata->reveal());

        $propertyMetadata = $this->prophesize(PropertyMetadata::class);
        $propertyMetadata->getType()->willReturn('text_line');
        $metadata->getProperty('title')->willReturn($propertyMetadata->reveal());

        $contentType = $this->prophesize(ContentTypeInterface::class);
        $contentTypeManager->get('text_line')->willReturn($contentType->reveal());

        $this->assertEquals([], $resolver->resolveViewData($object->reveal(), 'title', null));
    }

    public function testResolveContentData()
    {
        $metadataFactory = $this->prophesize(StructureMetadataFactoryInterface::class);
        $contentTypeManager = $this->prophesize(ContentTypeManagerInterface::class);
        $structureManager = $this->prophesize(StructureManagerInterface::class);
        $requestStack = $this->prophesize(RequestStack::class);

        $resolver = new PropertyResolver(
            $metadataFactory->reveal(),
            $contentTypeManager->reveal(),
            $structureManager->reveal(),
            $requestStack->reveal()
        );

        $object = $this->prophesize(ContentableInterface::class);
        $object->getStructureType()->willReturn('default');

        $metadata = $this->prophesize(StructureMetadata::class);
        $metadataFactory->getStructureMetadata('course', 'default')->willReturn($metadata->reveal());

        $propertyMetadata = $this->prophesize(PropertyMetadata::class);
        $propertyMetadata->getType()->willReturn('text_line');
        $metadata->getProperty('title')->willReturn($propertyMetadata->reveal());

        $structure = $this->prophesize(StructureInterface::class);
        $structure->setWebspaceKey('sulu_io')->shouldBeCalled();
        $property = $this->prophesize(PropertyInterface::class);
        $property->setValue('Sprungbrett is awesome')->shouldBeCalled();
        $structure->getProperty('title')->willReturn($property->reveal());
        $structureManager->wrapStructure('course', $metadata->reveal())->willReturn($structure->reveal());

        $requestAttributes = $this->prophesize(RequestAttributes::class);
        $requestAttributes->getAttribute('webspaceKey')->willReturn('sulu_io');
        $request = new Request([], [], ['_sulu' => $requestAttributes->reveal()]);
        $requestStack->getCurrentRequest()->willReturn($request);

        $contentType = $this->prophesize(ContentTypeInterface::class);
        $contentTypeManager->get('text_line')->willReturn($contentType->reveal());
        $contentType->getContentData($property->reveal())->willReturn('Sprungbrett is awesome');

        $this->assertEquals(
            'Sprungbrett is awesome',
            $resolver->resolveContentData($object->reveal(), 'title', 'Sprungbrett is awesome')
        );
    }

    public function testResolveContentDataNull()
    {
        $metadataFactory = $this->prophesize(StructureMetadataFactoryInterface::class);
        $contentTypeManager = $this->prophesize(ContentTypeManagerInterface::class);
        $structureManager = $this->prophesize(StructureManagerInterface::class);
        $requestStack = $this->prophesize(RequestStack::class);

        $resolver = new PropertyResolver(
            $metadataFactory->reveal(),
            $contentTypeManager->reveal(),
            $structureManager->reveal(),
            $requestStack->reveal()
        );

        $object = $this->prophesize(ContentableInterface::class);
        $object->getStructureType()->willReturn('default');

        $metadata = $this->prophesize(StructureMetadata::class);
        $metadataFactory->getStructureMetadata('course', 'default')->willReturn($metadata->reveal());

        $propertyMetadata = $this->prophesize(PropertyMetadata::class);
        $propertyMetadata->getType()->willReturn('text_line');
        $metadata->getProperty('title')->willReturn($propertyMetadata->reveal());

        $contentType = $this->prophesize(ContentTypeInterface::class);
        $contentTypeManager->get('text_line')->willReturn($contentType->reveal());
        $contentType->getDefaultValue()->willReturn('');

        $this->assertEquals('', $resolver->resolveContentData($object->reveal(), 'title', null));
    }
}
