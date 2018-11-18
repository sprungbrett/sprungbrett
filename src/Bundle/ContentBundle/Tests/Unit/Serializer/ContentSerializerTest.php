<?php

declare(strict_types=1);

namespace Sprungbrett\Bundle\ContentBundle\Tests\Unit\Serializer;

use JMS\Serializer\JsonSerializationVisitor;
use PHPUnit\Framework\TestCase;
use Sprungbrett\Bundle\ContentBundle\Model\Content\ContentInterface;
use Sprungbrett\Bundle\ContentBundle\Serializer\ContentHandler;
use Sulu\Component\Content\Metadata\Factory\StructureMetadataFactoryInterface;
use Sulu\Component\Content\Metadata\PropertyMetadata;
use Sulu\Component\Content\Metadata\StructureMetadata;

class ContentSerializerTest extends TestCase
{
    /**
     * @var StructureMetadataFactoryInterface
     */
    private $factory;

    /**
     * @var ContentHandler
     */
    private $handler;

    /**
     * @var JsonSerializationVisitor
     */
    private $visitor;

    /**
     * @var ContentInterface
     */
    private $content;

    protected function setUp()
    {
        $this->factory = $this->prophesize(StructureMetadataFactoryInterface::class);

        $this->handler = new ContentHandler($this->factory->reveal());

        $this->visitor = $this->prophesize(JsonSerializationVisitor::class);
        $this->content = $this->prophesize(ContentInterface::class);
        $this->content->getResourceKey()->willReturn('courses');
        $this->content->getResourceId()->willReturn('123-123-123');
        $this->content->getType()->willReturn('default');
    }

    public function testSerializeContentToJson(): void
    {
        $metadata = $this->prophesize(StructureMetadata::class);
        $property = $this->prophesize(PropertyMetadata::class);
        $property->getName()->willReturn('title');
        $metadata->getProperties()->willReturn([$property->reveal()]);

        $this->content->getData()->willReturn(['title' => 'Sprungbrett']);

        $this->factory->getStructureMetadata('courses', 'default')->willReturn($metadata->reveal());

        $this->handler->serializeContentToJson($this->visitor->reveal(), $this->content->reveal());

        $this->visitor->setRoot(['id' => '123-123-123', 'template' => 'default', 'title' => 'Sprungbrett'])
            ->shouldBeCalled();
    }

    public function testSerializeContentToJsonPropertyNotExists(): void
    {
        $metadata = $this->prophesize(StructureMetadata::class);
        $property = $this->prophesize(PropertyMetadata::class);
        $property->getName()->willReturn('title');
        $metadata->getProperties()->willReturn([$property->reveal()]);

        $this->content->getData()->willReturn([]);

        $this->factory->getStructureMetadata('courses', 'default')->willReturn($metadata->reveal());

        $this->handler->serializeContentToJson($this->visitor->reveal(), $this->content->reveal());

        $this->visitor->setRoot(['id' => '123-123-123', 'template' => 'default', 'title' => null])
            ->shouldBeCalled();
    }
}
