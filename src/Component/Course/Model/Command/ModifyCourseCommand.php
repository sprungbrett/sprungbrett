<?php

namespace Sprungbrett\Component\Course\Model\Command;

use Sprungbrett\Component\Payload\Model\Command\PayloadTrait;
use Sprungbrett\Component\Translation\Model\Command\LocaleTrait;
use Sprungbrett\Component\Uuid\Model\Command\IdTrait;

class ModifyCourseCommand
{
    use IdTrait;
    use LocaleTrait;
    use PayloadTrait;

    public function __construct(string $id, string $locale, array $payload)
    {
        $this->initializeId($id);
        $this->initializeLocale($locale);
        $this->initializePayload($payload);
    }

    public function getTitle(): string
    {
        return $this->getStringValue('title');
    }
}
