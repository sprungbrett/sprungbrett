<?php

namespace Sprungbrett\Bundle\CourseBundle;

use Sprungbrett\Component\Course\Model\CourseInterface;
use Sprungbrett\Component\Course\Model\CourseTranslationInterface;
use Sulu\Bundle\PersistenceBundle\PersistenceBundleTrait;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class SprungbrettCourseBundle extends Bundle
{
    use PersistenceBundleTrait;

    public function build(ContainerBuilder $container)
    {
        $this->buildPersistence(
            [
                CourseInterface::class => 'sulu.model.course.class',
                CourseTranslationInterface::class => 'sulu.model.course-translation.class',
            ],
            $container
        );
    }
}
