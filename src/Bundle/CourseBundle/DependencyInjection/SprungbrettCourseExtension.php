<?php

namespace Sprungbrett\Bundle\CourseBundle\DependencyInjection;

use Sprungbrett\Bundle\CourseBundle\Model\Attendee\CourseAttendeeInterface;
use Sprungbrett\Bundle\CourseBundle\Model\Course\Course;
use Sprungbrett\Bundle\CourseBundle\Model\Course\CourseInterface;
use Sprungbrett\Bundle\CourseBundle\Structure\CourseBridge;
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
                            'namespace_prefix' => 'Sprungbrett\\Bundle\\CourseBundle\\Model',
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
                            CourseInterface::class,
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
                    'course_attendee' => [
                        'type' => 'workflow',
                        'marking_store' => [
                            'type' => 'single_state',
                            'arguments' => [
                                'workflowStage',
                            ],
                        ],
                        'supports' => [
                            CourseAttendeeInterface::class,
                        ],
                        'places' => [
                            CourseAttendeeInterface::WORKFLOW_STAGE_NEW,
                            CourseAttendeeInterface::WORKFLOW_STAGE_INTERESTED,
                        ],
                        'transitions' => [
                            CourseAttendeeInterface::TRANSITION_SHOW_INTEREST => [
                                'from' => CourseAttendeeInterface::WORKFLOW_STAGE_NEW,
                                'to' => CourseAttendeeInterface::WORKFLOW_STAGE_INTERESTED,
                            ],
                        ],
                    ],
                ],
            ]
        );

        if (!$container->hasExtension('doctrine')) {
            throw new \RuntimeException('Missing DoctrineBundle.');
        }

        $container->prependExtensionConfig(
            'doctrine',
            [
                'orm' => [
                    'entity_managers' => [
                        'default' => [
                            'mappings' => [
                                'SprungbrettCourseBundle' => [
                                    'type' => 'xml',
                                    'prefix' => 'Sprungbrett\\Bundle\\CourseBundle\\Model',
                                    'dir' => 'Resources/config/doctrine',
                                    'alias' => 'SprungbrettCourseBundle',
                                    'is_bundle' => true,
                                    'mapping' => true,
                                ],
                            ],
                        ],
                    ],
                ],
            ]
        );

        if (!$container->hasExtension('sulu_route')) {
            throw new \RuntimeException('Missing SuluRouteBundle.');
        }

        $container->prependExtensionConfig(
            'sulu_route',
            [
                'mappings' => [
                    Course::class => [
                        // TODO extensible class?
                        'generator' => 'schema',
                        'options' => [
                            'route_schema' => '/{object.getName()}',
                        ],
                    ],
                ],
            ]
        );

        if (!$container->hasExtension('sulu_core')) {
            throw new \RuntimeException('Missing SuluCoreBundle.');
        }

        $container->prependExtensionConfig(
            'sulu_core',
            [
                'content' => [
                    'structure' => [
                        'type_map' => [
                            'course' => CourseBridge::class,
                        ],
                        'resources' => [
                            'course_contents' => [
                                // FIXME in sulu
                                'datagrid' => Course::class, // TODO extensible class?
                                'types' => ['course'],
                                'endpoint' => 'sprungbrett.get_courses',
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
                'field_type_options' => [
                    'single_selection' => [
                        'single_trainer_selection' => [
                            'default_type' => 'auto_complete',
                            'resource_key' => 'contacts',
                            'types' => [
                                'auto_complete' => [
                                    'display_property' => 'fullName',
                                    'search_properties' => ['mainEmail', 'firstName', 'lastName'],
                                ],
                            ],
                        ],
                    ],
                ],
                'resources' => [
                    'courses' => [
                        'form' => ['@SprungbrettCourseBundle/Resources/config/forms/Course.xml'],
                        'datagrid' => '%sulu.model.course.class%',
                        'endpoint' => 'sprungbrett.get_courses',
                    ],
                    'trainers' => [
                        'form' => ['@SprungbrettCourseBundle/Resources/config/forms/Trainer.xml'],
                        'endpoint' => 'sprungbrett.get_trainers',
                    ],
                    'attendees' => [
                        'form' => ['@SprungbrettCourseBundle/Resources/config/forms/Attendee.xml'],
                        'endpoint' => 'sprungbrett.get_attendees',
                    ],
                ],
            ]
        );
    }
}
