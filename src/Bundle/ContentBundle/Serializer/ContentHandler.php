<?php

declare(strict_types=1);

namespace Sprungbrett\Bundle\ContentBundle\Serializer;

use JMS\Serializer\GraphNavigator;
use JMS\Serializer\Handler\SubscribingHandlerInterface;
use JMS\Serializer\JsonSerializationVisitor;
use Sprungbrett\Bundle\ContentBundle\Model\Content\Content;
use Sprungbrett\Bundle\ContentBundle\Model\Content\ContentInterface;
use Sulu\Component\Content\Metadata\Factory\StructureMetadataFactoryInterface;

class ContentHandler implements SubscribingHandlerInterface
{
    public static function getSubscribingMethods()
    {
        return [
            [
                'direction' => GraphNavigator::DIRECTION_SERIALIZATION,
                'format' => 'json',
                'type' => Content::class,
                'method' => 'serializeContentToJson',
            ],
        ];
    }

    /**
     * @var StructureMetadataFactoryInterface
     */
    private $factory;

    public function __construct(StructureMetadataFactoryInterface $factory)
    {
        $this->factory = $factory;
    }

    public function serializeContentToJson(JsonSerializationVisitor $visitor, ContentInterface $content): void
    {
        $metadata = $this->factory->getStructureMetadata($content->getResourceKey(), $content->getType());
        if (!$metadata) {
            return;
        }

        $data = ['template' => $content->getType()];
        foreach ($metadata->getProperties() as $property) {
            $name = $property->getName();
            $data[$name] = isset($content->getData()[$name]) ? $content->getData()[$name] : null;
        }

        $visitor->setRoot($data);
    }
}
