<?php

namespace Sprungbrett\Component\Content\Tests\Asset;

use Sprungbrett\Component\Content\Model\ContentableInterface;
use Sprungbrett\Component\Content\Model\ContentableTrait;
use Sprungbrett\Component\Content\Model\ContentInterface;

class ContentableAsset implements ContentableInterface
{
    use ContentableTrait;

    public function __construct(ContentInterface $content)
    {
        $this->initializeContent($content);
    }
}
