<?php

declare(strict_types=1);

namespace Sprungbrett\Bundle\CourseBundle\DependencyInjection;

use Sprungbrett\Bundle\CourseBundle\Model\Course\Course;
use Sprungbrett\Bundle\CourseBundle\Model\Course\CourseTranslation;
use Sprungbrett\Bundle\CourseBundle\Model\Schedule\Schedule;
use Sprungbrett\Bundle\CourseBundle\Model\Schedule\ScheduleTranslation;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use Symfony\Component\Yaml\Yaml;

class SprungbrettCourseExtension extends Extension implements PrependExtensionInterface
{
    public function load(array $configs, ContainerBuilder $container)
    {
        $locator = new FileLocator(__DIR__ . '/../Resources/config');
        $loader = new XmlFileLoader($container, $locator);

        $container->setParameter('sprungbrett.model.course', Course::class);
        $container->setParameter('sprungbrett.model.course_translation', CourseTranslation::class);

        $container->setParameter('sprungbrett.model.schedule', Schedule::class);
        $container->setParameter('sprungbrett.model.schedule_translation', ScheduleTranslation::class);

        $loader->load('services.xml');
    }

    public function prepend(ContainerBuilder $container)
    {
        if (!$container->hasExtension('sulu_core')) {
            throw new \RuntimeException('Missing SuluCoreBundle');
        }

        $config = Yaml::parseFile(__DIR__ . '/../Resources/config/packages/sulu_core.yaml');
        $container->prependExtensionConfig('sulu_core', $config['sulu_core']);

        if (!$container->hasExtension('jms_serializer')) {
            throw new \RuntimeException('Missing JmsSerializerBundle');
        }

        $config = Yaml::parseFile(__DIR__ . '/../Resources/config/packages/jms_serializer.yaml');
        $container->prependExtensionConfig('jms_serializer', $config['jms_serializer']);

        if (!$container->hasExtension('doctrine')) {
            throw new \RuntimeException('Missing DoctrineBundle');
        }

        $config = Yaml::parseFile(__DIR__ . '/../Resources/config/packages/doctrine.yaml');
        $container->prependExtensionConfig('doctrine', $config['doctrine']);

        // optional sulu_admin
        if ($container->hasExtension('sulu_admin')) {
            $config = Yaml::parseFile(__DIR__ . '/../Resources/config/packages/sulu_admin.yaml');
            $container->prependExtensionConfig('sulu_admin', $config['sulu_admin']);
        }
    }
}
