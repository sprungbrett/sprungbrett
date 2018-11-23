<?php

declare(strict_types=1);

namespace Sprungbrett\Bundle\PortalBundle\Controller;

use Sprungbrett\Bundle\ContentBundle\Model\Content\ContentInterface;
use Sprungbrett\Bundle\PortalBundle\Model\CourseView\CourseViewInterface;
use Sulu\Component\Content\Compat\StructureInterface;
use Sulu\Component\Content\Compat\StructureManagerInterface;
use Sulu\Component\Content\ContentTypeManagerInterface;

trait CourseViewTrait
{
    protected function resolveCourse(CourseViewInterface $object): array
    {
        $attributes = $this->resolveContent($object->getContent());
        $attributes['id'] = $object->getUuid();
        $attributes['course'] = $object->getCourse();
        $attributes['schedules'] = $object->getSchedules();
        $attributes['route'] = $object->getRoute()->getPath();

        return $attributes;
    }

    private function resolveContent(ContentInterface $content): array
    {
        $contentTypeManager = $this->getContentTypeManager();
        $structure = $this->getStructure($content);
        if (!$structure) {
            return [];
        }

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

        $structureManager = $this->getStructureManager();
        $structure = $structureManager->getStructure($contentType, $content->getResourceKey());

        $locale = $content->getCurrentLocale();
        if ($locale) {
            $structure->setLanguageCode($locale);
        }

        return $structure;
    }

    private function getContentTypeManager(): ContentTypeManagerInterface
    {
        /** @var ContentTypeManagerInterface $contentTypeManager */
        $contentTypeManager = $this->get('sulu.content.type_manager');

        return $contentTypeManager;
    }

    private function getStructureManager(): StructureManagerInterface
    {
        /** @var StructureManagerInterface $structureManager */
        $structureManager = $this->get('sulu.content.structure_manager');

        return $structureManager;
    }
}
