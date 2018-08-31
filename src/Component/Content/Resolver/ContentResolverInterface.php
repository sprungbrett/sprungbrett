<?php

namespace Sprungbrett\Component\Content\Resolver;

use Sprungbrett\Component\Content\Model\ContentableInterface;

interface ContentResolverInterface
{
    public function resolve(ContentableInterface $contentable): array;
}
