<?php

declare(strict_types=1);

namespace Sprungbrett\Bundle\PortalBundle\Model\CourseView\Exception;

use Sprungbrett\Bundle\PortalBundle\Model\CourseView\CourseViewInterface;

/**
 * TODO extract ModelNotFoundException.
 */
class CourseViewNotFoundException extends \Exception
{
    /**
     * @var string
     */
    private $uuid;

    public function __construct(string $uuid, $code = 0, \Throwable $previous = null)
    {
        parent::__construct(
            sprintf('Model "%s" with id "%s" not found', CourseViewInterface::class, $uuid),
            $code,
            $previous
        );

        $this->uuid = $uuid;
    }

    public function getUuid(): string
    {
        return $this->uuid;
    }
}
