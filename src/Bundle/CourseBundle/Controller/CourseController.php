<?php

namespace Sprungbrett\Bundle\CourseBundle\Controller;

use FOS\RestBundle\Controller\Annotations\NamePrefix;
use FOS\RestBundle\Routing\ClassResourceInterface;
use Sprungbrett\Component\Resource\Controller\ResourceController;

/**
 * @NamePrefix("sprungbrett.")
 */
class CourseController extends ResourceController implements ClassResourceInterface
{
}
