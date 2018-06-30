<?php

namespace Sprungbrett\Bundle\CourseBundle;

use Sprungbrett\Bundle\CourseBundle\DependencyInjection\CompilerPass\SetDefaultTypeCompilerPass;
use Sprungbrett\Bundle\CourseBundle\Model\Course\CourseInterface;
use Sprungbrett\Bundle\CourseBundle\Model\Course\CourseTranslationInterface;
use Sulu\Bundle\PersistenceBundle\PersistenceBundleTrait;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class SprungbrettCourseBundle extends Bundle
{
    use PersistenceBundleTrait;

    public function build(ContainerBuilder $container)
    {
        $container->addCompilerPass(new SetDefaultTypeCompilerPass());

        $this->buildPersistence(
            [
                CourseInterface::class => 'sulu.model.course.class',
                CourseTranslationInterface::class => 'sulu.model.course-translation.class',
            ],
            $container
        );
    }
}
