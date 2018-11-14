<?php

namespace Sprungbrett\Bundle\CourseBundle\DependencyInjection;

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

        $loader->load('services.xml');
    }

    public function prepend(ContainerBuilder $container)
    {
        if (!$container->hasExtension('sulu_admin')) {
            throw new \RuntimeException('Missing SuluAdminBundle');
        }

        $config = Yaml::parseFile(__DIR__ . '/../Resources/config/packages/sulu_admin.yaml');
        $container->prependExtensionConfig('sulu_admin', $config['sulu_admin']);

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
    }
}
