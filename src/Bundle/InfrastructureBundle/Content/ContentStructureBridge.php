<?php

namespace Sprungbrett\Bundle\InfrastructureBundle\Content;

use DateTime;
use Sprungbrett\Component\Content\Model\ContentableInterface;
use Sprungbrett\Component\Translation\Model\TranslatableInterface;
use Sulu\Component\Content\Compat\PropertyInterface;
use Sulu\Component\Content\Compat\StructureInterface;
use Sulu\Component\Content\Metadata\PropertyMetadata;
use Sulu\Component\Content\Metadata\StructureMetadata;
use Sulu\Component\Persistence\Model\TimestampableInterface;
use Sulu\Component\Persistence\Model\UserBlameInterface;

class ContentStructureBridge implements StructureInterface
{
    /**
     * @var ContentableInterface
     */
    private $object;

    /**
     * @var StructureMetadata
     */
    private $metadata;

    /**
     * @var string
     */
    private $webspaceKey;

    public function __construct(ContentableInterface $object, StructureMetadata $metadata, string $webspaceKey)
    {
        $this->object = $object;
        $this->metadata = $metadata;
        $this->webspaceKey = $webspaceKey;
    }

    public function setLanguageCode($language)
    {
        $this->readOnlyException(__METHOD__);
    }

    public function getLanguageCode(): ?string
    {
        if (!$this->object instanceof TranslatableInterface) {
            return null;
        }

        return $this->object->getLocale();
    }

    public function setWebspaceKey($webspace)
    {
        $this->readOnlyException(__METHOD__);
    }

    public function getWebspaceKey()
    {
        $this->webspaceKey;
    }

    public function getUuid(): ?string
    {
        if (!method_exists($this->object, 'getId')) {
            return null;
        }

        return $this->object->getId();
    }

    public function setUuid($uuid)
    {
        $this->readOnlyException(__METHOD__);
    }

    public function getCreator(): ?int
    {
        if (!$this->object instanceof UserBlameInterface || !$this->object->getCreator()) {
            return null;
        }

        return $this->object->getCreator()->getId();
    }

    public function setCreator($userId)
    {
        $this->readOnlyException(__METHOD__);
    }

    public function getChanger(): ?int
    {
        if (!$this->object instanceof UserBlameInterface || !$this->object->getChanger()) {
            return null;
        }

        return $this->object->getChanger()->getId();
    }

    public function setChanger($userId)
    {
        $this->readOnlyException(__METHOD__);
    }

    public function getCreated(): DateTime
    {
        if (!$this->object instanceof TimestampableInterface) {
            return null;
        }

        return $this->object->getCreated();
    }

    public function setCreated(DateTime $created)
    {
        $this->readOnlyException(__METHOD__);
    }

    public function getChanged()
    {
        if (!$this->object instanceof TimestampableInterface) {
            return null;
        }

        return $this->object->getChanged();
    }

    public function setChanged(DateTime $changed)
    {
        $this->readOnlyException(__METHOD__);
    }

    public function getKey()
    {
        return $this->metadata->getName();
    }

    public function getProperty($name)
    {
        return new ContentPropertyBridge($this->metadata->getProperty($name), $this);
    }

    public function hasProperty($name): bool
    {
        return $this->metadata->hasProperty($name);
    }

    public function getProperties($flatten = false)
    {
        $items = $this->metadata->getChildren();
        if ($flatten) {
            $items = $this->metadata->getProperties();
        }

        return array_map(
            function (PropertyMetadata $propertyMetadata) {
                return $this->getProperty($propertyMetadata->getName());
            },
            $items
        );
    }

    public function setHasChildren($hasChildren)
    {
        $this->readOnlyException(__METHOD__);
    }

    public function getHasChildren()
    {
        return false;
    }

    public function setChildren($children)
    {
        $this->readOnlyException(__METHOD__);
    }

    public function getChildren()
    {
        $this->notImplementedException(__METHOD__);
    }

    public function getPublishedState()
    {
        $this->notImplementedException(__METHOD__);
    }

    public function setPublished($published)
    {
        $this->readOnlyException(__METHOD__);
    }

    public function getPublished()
    {
        $this->notImplementedException(__METHOD__);
    }

    public function getPropertyValue($name)
    {
        $this->notImplementedException(__METHOD__);
    }

    public function getPropertyNames()
    {
        return array_map(
            function (PropertyMetadata $propertyMetadata) {
                return $propertyMetadata->getName();
            },
            $this->metadata->getProperties()
        );
    }

    public function setType($type)
    {
        $this->readOnlyException(__METHOD__);
    }

    public function getType()
    {
        $this->notImplementedException(__METHOD__);
    }

    public function getPath()
    {
        $this->notImplementedException(__METHOD__);
    }

    public function setPath($path)
    {
        $this->readOnlyException(__METHOD__);
    }

    public function setHasTranslation($hasTranslation)
    {
        $this->readOnlyException(__METHOD__);
    }

    public function getHasTranslation()
    {
        $this->notImplementedException(__METHOD__);
    }

    public function toArray($complete = true)
    {
        $this->notImplementedException(__METHOD__);
    }

    public function getPropertyByTagName($tagName, $highest = true): PropertyInterface
    {
        return $this->getProperty($this->metadata->getPropertyByTagName($tagName, $highest));
    }

    public function getPropertiesByTagName($tagName): array
    {
        return array_map(
            function (PropertyMetadata $propertyMetadata) {
                return $this->getProperty($propertyMetadata->getName());
            },
            $this->metadata->getPropertiesByTagName($tagName)
        );
    }

    public function getPropertyValueByTagName($tagName)
    {
        $this->notImplementedException(__METHOD__);
    }

    public function hasTag($tag): bool
    {
        return $this->metadata->hasTag($tag);
    }

    public function getNodeType()
    {
        $this->notImplementedException(__METHOD__);
    }

    public function getNodeName()
    {
        $this->notImplementedException(__METHOD__);
    }

    public function getLocalizedTitle($languageCode)
    {
        $this->notImplementedException(__METHOD__);
    }

    public function getNodeState()
    {
        $this->notImplementedException(__METHOD__);
    }

    public function copyFrom(StructureInterface $structure)
    {
        $this->notImplementedException(__METHOD__);
    }

    public function jsonSerialize()
    {
        $this->notImplementedException(__METHOD__);
    }

    protected function readOnlyException(string $method): void
    {
        throw new \BadMethodCallException(
            sprintf(
                'Compatibility layer StructureBridge instances are readonly. Tried to call "%s"',
                $method
            )
        );
    }

    protected function notImplementedException(string $method): void
    {
        throw new \BadMethodCallException(
            sprintf(
                'Compatibility layer ContentStructureBridge does not implement "%s".',
                $method
            )
        );
    }
}
