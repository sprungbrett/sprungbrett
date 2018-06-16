<?php

namespace Sprungbrett\Bundle\CourseBundle\DependencyInjection;

use Sulu\Bundle\PersistenceBundle\DependencyInjection\PersistenceExtensionTrait;
use Sulu\Component\HttpKernel\SuluKernel;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;

class SprungbrettCourseExtension extends Extension implements PrependExtensionInterface
{
    use PersistenceExtensionTrait;

    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new XmlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $loader->load('services.xml');
        $loader->load('handler.xml');

        $this->configurePersistence($config['objects'], $container);
    }

    public function prepend(ContainerBuilder $container)
    {
        if (!$container->hasExtension('jms_serializer')) {
            throw new \RuntimeException('Missing JmsSerializerBundle.');
        }

        $container->prependExtensionConfig(
            'jms_serializer',
            [
                'metadata' => [
                    'directories' => [
                        [
                            'name' => 'SprungbrettCourseBundle',
                            'path' => __DIR__ . '/../Resources/config/serializer',
                            'namespace_prefix' => 'Sprungbrett\\Bundle\\CourseBundle\\Entity',
                        ],
                    ],
                ],
            ]
        );

        $this->prependAdmin($container);
    }

    private function prependAdmin(ContainerBuilder $container)
    {
        if (SuluKernel::CONTEXT_ADMIN !== $container->getParameter('sulu.context')) {
            return;
        }

        if (!$container->hasExtension('sulu_admin')) {
            throw new \RuntimeException('Missing SuluAdminBundle.');
        }

        $container->prependExtensionConfig(
            'sulu_admin',
            [
                'resources' => [
                    'courses' => [
                        'form' => ['@SprungbrettCourseBundle/Resources/config/forms/Course.xml'],
                        'datagrid' => '%sulu.model.course.class%',
                        'endpoint' => 'sprungbrett.get_courses',
                    ],
                ],
            ]
        );
    }
}
