<?php

declare(strict_types=1);

namespace Sprungbrett\Bundle\ContentBundle\Model\Seo\Message;

class RemoveSeoMessage
{
    /**
     * @var string
     */
    private $resourceKey;

    /**
     * @var string
     */
    private $resourceId;

    public function __construct(string $resourceKey, string $resourceId)
    {
        $this->resourceKey = $resourceKey;
        $this->resourceId = $resourceId;
    }

    public function getResourceKey(): string
    {
        return $this->resourceKey;
    }

    public function getResourceId(): string
    {
        return $this->resourceId;
    }
}
