<?php

namespace Sprungbrett\Bundle\PortalBundle\Controller;

use Sprungbrett\Bundle\ContentBundle\Model\Content\ContentInterface;
use Sprungbrett\Bundle\PortalBundle\Model\CourseView\CourseViewInterface;
use Sulu\Component\Content\Compat\StructureInterface;

trait CourseViewTrait
{
    protected function resolveCourse(CourseViewInterface $object): array
    {
        $attributes = $this->resolveContent($object->getContent());
        $attributes['id'] = $object->getUuid();
        $attributes['course'] = $object->getCourse();
        $attributes['route'] = $object->getRoute()->getPath();

        return $attributes;
    }

    private function resolveContent(ContentInterface $content): array
    {
        $contentTypeManager = $this->get('sulu.content.type_manager');
        $structure = $this->getStructure($content);

        $data = $content->getData();

        $content = $view = [];
        foreach ($structure->getProperties(true) as $property) {
            if (array_key_exists($property->getName(), $data)) {
                $property->setValue($data[$property->getName()]);
            }

            $contentType = $contentTypeManager->get($property->getContentTypeName());
            $content[$property->getName()] = $contentType->getContentData($property);
            $view[$property->getName()] = $contentType->getViewData($property);
        }

        return ['template' => $structure->getKey(), 'content' => $content, 'view' => $view];
    }

    private function getStructure(ContentInterface $content): ?StructureInterface
    {
        $contentType = $content->getType();
        if (!$contentType) {
            return null;
        }

        $structureManager = $this->get('sulu.content.structure_manager');
        $structure = $structureManager->getStructure($contentType, $content->getResourceKey());
        $structure->setLanguageCode($content->getLocale());

        return $structure;
    }

    abstract protected function get(string $id);
}
