<?php

namespace Sprungbrett\Bundle\CourseBundle\Model\Message;

use Sprungbrett\Component\Payload\Model\Message\PayloadTrait;

class ModifyCourseMessage
{
    use PayloadTrait {
        __construct as protected initializePayload;
    }

    /**
     * @var string
     */
    private $uuid;

    /**
     * @var string
     */
    private $locale;

    public function __construct(string $uuid, string $locale, array $payload)
    {
        $this->uuid = $uuid;
        $this->locale = $locale;

        $this->initializePayload($payload);
    }

    public function getUuid(): string
    {
        return $this->uuid;
    }

    public function getLocale():string
    {
        return $this->locale;
    }

    public function getName(): string
    {
        return $this->getStringValue('name');
    }
}
