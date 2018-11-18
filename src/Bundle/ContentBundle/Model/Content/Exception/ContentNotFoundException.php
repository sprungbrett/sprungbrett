<?php

declare(strict_types=1);

namespace Sprungbrett\Bundle\ContentBundle\Model\Content\Exception;

use Sprungbrett\Bundle\ContentBundle\Model\Content\ContentInterface;

class ContentNotFoundException extends \Exception
{
    /**
     * @var string
     */
    private $resourceKey;

    /**
     * @var string
     */
    private $resourceId;

    public function __construct(string $resourceKey, string $resourceId, $code = 0, \Throwable $previous = null)
    {
        parent::__construct(
            sprintf('Model "%s" with id "%s-%s" not found', ContentInterface::class, $resourceKey, $resourceId),
            $code,
            $previous
        );

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
