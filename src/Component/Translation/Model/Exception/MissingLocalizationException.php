<?php

namespace Sprungbrett\Component\Translation\Model\Exception;

use Throwable;

class MissingLocalizationException extends \Exception
{
    public function __construct(int $code = 0, Throwable $previous = null)
    {
        parent::__construct('Missing localization.', $code, $previous);
    }
}
