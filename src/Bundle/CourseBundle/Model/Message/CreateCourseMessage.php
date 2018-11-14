<?php

namespace Sprungbrett\Bundle\CourseBundle\Model\Message;

use Ramsey\Uuid\Uuid;
use Sprungbrett\Component\Payload\Model\Message\PayloadTrait;

class CreateCourseMessage
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

    public function __construct(string $locale, array $payload)
    {
        $this->uuid = Uuid::uuid4()->toString();
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
