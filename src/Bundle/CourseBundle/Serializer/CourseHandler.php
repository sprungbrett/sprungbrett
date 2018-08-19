<?php

namespace Sprungbrett\Bundle\CourseBundle\Serializer;

use JMS\Serializer\Context;
use JMS\Serializer\Metadata\PropertyMetadata;
use Metadata\MethodMetadata;
use Sprungbrett\Bundle\CourseBundle\Model\Course\CourseInterface;
use Sprungbrett\Component\Content\Resolver\ContentResolverInterface;
use Sulu\Component\Serializer\ArraySerializationVisitor;

class CourseHandler
{
    /**
     * @var ContentResolverInterface
     */
    private $contentResolver;

    public function __construct(ContentResolverInterface $contentResolver)
    {
        $this->contentResolver = $contentResolver;
    }

    public function serializeToArray(
        ArraySerializationVisitor $visitor,
        CourseInterface $course,
        array $type,
        Context $context
    ): array {
        $result = $this->contentResolver->resolve($course);
        $result['course'] = $this->serializeCourse($course, $context);

        return $result;
    }

    private function serializeCourse(CourseInterface $course, Context $context): array
    {
        $metadata = $context->getMetadataFactory()->getMetadataForClass(get_class($course));

        $result = [];
        /** @var PropertyMetadata $propertyMetadata */
        foreach ($metadata->propertyMetadata as $propertyMetadata) {
            $value = $propertyMetadata->getValue($course);
            $result[$propertyMetadata->name] = $context->getNavigator()->accept($value, null, $context);
        }

        /** @var MethodMetadata $methodMetadata */
        foreach ($metadata->methodMetadata as $methodMetadata) {
            $value = $methodMetadata->invoke($course);
            $result[$propertyMetadata->name] = $context->getNavigator()->accept($value, null, $context);
        }

        return $result;
    }
}
