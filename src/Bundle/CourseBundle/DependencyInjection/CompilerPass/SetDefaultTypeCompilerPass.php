<?php

namespace Sprungbrett\Bundle\CourseBundle\DependencyInjection\CompilerPass;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class SetDefaultTypeCompilerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        $defaultTypes = $container->getParameter('sulu.content.structure.default_types');
        $container->setParameter('sprungbrett_course.default_type', $defaultTypes['course']);
    }
}
