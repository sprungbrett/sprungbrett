<?php

namespace Sprungbrett\Bundle\CourseBundle\Model\Exception;

use Sprungbrett\Bundle\CourseBundle\Model\CourseInterface;

/**
 * TODO extract ModelNotFoundException.
 */
class CourseNotFoundException extends \Exception
{
    /**
     * @var string
     */
    private $uuid;

    public function __construct(string $uuid, $code = 0, \Throwable $previous = null)
    {
        parent::__construct(
            sprintf('Model "%s" with id "%s" not found', CourseInterface::class, $uuid),
            $code,
            $previous
        );

        $this->uuid = $uuid;
    }

    public function getUuid(): string
    {
        return $this->id;
    }
}

