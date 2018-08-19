<?php

namespace Sprungbrett\Component\Content\Resolver;

use Sprungbrett\Component\Content\Model\ContentableInterface;

class ContentResolver implements ContentResolverInterface
{
    /**
     * @var PropertyResolverInterface
     */
    private $propertyResolver;

    public function __construct(PropertyResolverInterface $propertyResolver)
    {
        $this->propertyResolver = $propertyResolver;
    }

    public function resolve(ContentableInterface $contentable): array
    {
        $content = [];
        $view = [];

        $structureType = $contentable->getStructureType();

        foreach ($contentable->getContentData() as $key => $value) {
            $content[$key] = $this->propertyResolver->resolveContentData($structureType, $key, $value);
            $view[$key] = $this->propertyResolver->resolveViewData($structureType, $key, $value);
        }

        return ['content' => $content, 'view' => $view];
    }
}
