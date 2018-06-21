<?php

namespace Sprungbrett\Bundle\CourseBundle\DependencyInjection;

use Sprungbrett\Bundle\CourseBundle\Entity\Course;
use Sprungbrett\Component\Course\Model\CourseInterface;
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
                            'namespace_prefix' => 'Sprungbrett\\Component\\Course\\Model',
                        ],
                    ],
                ],
            ]
        );

        if (!$container->hasExtension('framework')) {
            throw new \RuntimeException('Missing FrameworkBundle.');
        }

        $container->prependExtensionConfig(
            'framework',
            [
                'workflows' => [
                    'course' => [
                        'type' => 'workflow',
                        'marking_store' => [
                            'type' => 'single_state',
                            'arguments' => [
                                'workflowStage',
                            ],
                        ],
                        'supports' => [
                            Course::class,
                        ],
                        'places' => [
                            CourseInterface::WORKFLOW_STAGE_NEW,
                            CourseInterface::WORKFLOW_STAGE_TEST,
                            CourseInterface::WORKFLOW_STAGE_PUBLISHED,
                        ],
                        'transitions' => [
                            CourseInterface::TRANSITION_CREATE => [
                                'from' => CourseInterface::WORKFLOW_STAGE_NEW,
                                'to' => CourseInterface::WORKFLOW_STAGE_TEST,
                            ],
                            CourseInterface::TRANSITION_PUBLISH => [
                                'from' => CourseInterface::WORKFLOW_STAGE_TEST,
                                'to' => CourseInterface::WORKFLOW_STAGE_PUBLISHED,
                            ],
                            CourseInterface::TRANSITION_UNPUBLISH => [
                                'from' => CourseInterface::WORKFLOW_STAGE_PUBLISHED,
                                'to' => CourseInterface::WORKFLOW_STAGE_TEST,
                            ],
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
