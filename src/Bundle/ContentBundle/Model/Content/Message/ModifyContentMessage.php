<?php

declare(strict_types=1);

namespace Sprungbrett\Bundle\ContentBundle\Model\Content\Message;

use Sprungbrett\Component\Payload\Model\Message\PayloadTrait;

class ModifyContentMessage
{
    use PayloadTrait {
     __construct as protected initializePayload;
    }

    /**
     * @var string
     */
    private $resourceKey;

    /**
     * @var string
     */
    private $resourceId;

    /**
     * @var string
     */
    private $locale;

    public function __construct(string $resourceKey, string $resourceId, string $locale, array $payload)
    {
        $this->resourceKey = $resourceKey;
        $this->resourceId = $resourceId;
        $this->locale = $locale;

        $this->initializePayload($payload);
    }

    public function getResourceKey(): string
    {
        return $this->resourceKey;
    }

    public function getResourceId(): string
    {
        return $this->resourceId;
    }

    public function getLocale(): string
    {
        return $this->locale;
    }

    public function getType(): string
    {
        return $this->getStringValue('type');
    }

    public function getData(): array
    {
        return $this->getArrayValue('data');
    }
}
