<?php

namespace Sprungbrett\Bundle\InfrastructureBundle\Content;

use Sulu\Component\Content\Compat\PropertyInterface;
use Sulu\Component\Content\Compat\StructureInterface;
use Sulu\Component\Content\Metadata\PropertyMetadata;

class ContentPropertyBridge implements PropertyInterface
{
    /**
     * @var PropertyMetadata
     */
    private $propertyMetadata;

    /**
     * @var StructureInterface
     */
    private $structure;

    /**
     * @var mixed
     */
    private $value;

    public function __construct(PropertyMetadata $propertyMetadata, StructureInterface $structure)
    {
        $this->propertyMetadata = $propertyMetadata;
        $this->structure = $structure;
    }

    public function toArray($depth = null)
    {
        $this->notImplementedException(__METHOD__);
    }

    public function getName()
    {
        return $this->propertyMetadata->getName();
    }

    public function isMandatory()
    {
        return $this->propertyMetadata->isRequired();
    }

    public function isMultilingual()
    {
        return $this->propertyMetadata->isLocalized();
    }

    public function getMinOccurs()
    {
        return $this->propertyMetadata->getMinOccurs();
    }

    public function getMaxOccurs()
    {
        return $this->propertyMetadata->getMaxOccurs();
    }

    public function getContentTypeName()
    {
        return $this->propertyMetadata->getType();
    }

    public function getParams()
    {
        return $this->propertyMetadata->getParameters();
    }

    public function setValue($value)
    {
        $this->value = $value;
    }

    public function getValue()
    {
        return $this->value;
    }

    public function getIsBlock()
    {
        return false;
    }

    public function getIsMultiple()
    {
        return $this->propertyMetadata->isMultiple();
    }

    public function getMandatory()
    {
        return $this->propertyMetadata->isRequired();
    }

    public function getMultilingual()
    {
        return $this->propertyMetadata->isMultiple();
    }

    public function getTags()
    {
        return $this->propertyMetadata->getTags();
    }

    public function getTag($tagName)
    {
        return $this->propertyMetadata->getTag($tagName);
    }

    public function getColspan()
    {
        return $this->propertyMetadata->getColSpan();
    }

    public function getTitle($languageCode)
    {
        return $this->propertyMetadata->getTitle($languageCode);
    }

    public function getInfoText($languageCode)
    {
        $this->notImplementedException(__METHOD__);
    }

    public function getPlaceholder($languageCode)
    {
        $this->notImplementedException(__METHOD__);
    }

    public function getStructure()
    {
        return $this->structure;
    }

    public function setStructure($structure)
    {
        $this->readOnlyException(__METHOD__);
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
