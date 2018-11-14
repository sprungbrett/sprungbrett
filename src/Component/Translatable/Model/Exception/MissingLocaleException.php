<?php

namespace Sprungbrett\Component\Translatable\Model\Exception;

class MissingLocaleException extends \Exception
{
    public function __construct(int $code = 0, \Throwable $previous = null)
    {
        parent::__construct('Missing locale.', $code, $previous);
    }
}
