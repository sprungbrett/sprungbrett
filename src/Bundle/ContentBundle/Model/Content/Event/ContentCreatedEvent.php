<?php

declare(strict_types=1);

namespace Sprungbrett\Bundle\ContentBundle\Model\Content\Event;

use Sprungbrett\Bundle\ContentBundle\Model\Content\ContentInterface;

class ContentCreatedEvent
{
    /**
     * @var ContentInterface
     */
    private $content;

    public function __construct(ContentInterface $content)
    {
        $this->content = $content;
    }

    public function getContent(): ContentInterface
    {
        return $this->content;
    }
}
