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
        $content = $view = [];

        $contentData = $contentable->getContentData();
        foreach ($this->propertyResolver->getAvailableProperties($contentable) as $property) {
            $value = array_key_exists($property, $contentData) ? $contentData[$property] : null;

            $content[$property] = $this->propertyResolver->resolveContentData($contentable, $property, $value);
            $view[$property] = $this->propertyResolver->resolveViewData($contentable, $property, $value);
        }

        return ['content' => $content, 'view' => $view];
    }
}
