<?php

namespace Sprungbrett\Component\Content\Resolver;

use Sprungbrett\Component\Content\Model\ContentableInterface;

interface PropertyResolverInterface
{
    /**
     * @param mixed $value
     *
     * @return mixed
     */
    public function resolveContentData(ContentableInterface $object, string $propertyName, $value);

    /**
     * @param mixed $value
     *
     * @return mixed
     */
    public function resolveViewData(ContentableInterface $object, string $propertyName, $value);
}
