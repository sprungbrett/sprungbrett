<?php

namespace Sprungbrett\Bundle\CourseBundle\Tests\Unit\DependencyInjection;

use Doctrine\Bundle\DoctrineBundle\DependencyInjection\DoctrineExtension;
use JMS\SerializerBundle\DependencyInjection\JMSSerializerExtension;
use Matthias\SymfonyDependencyInjectionTest\PhpUnit\AbstractExtensionTestCase;
use Sprungbrett\Bundle\CourseBundle\Admin\SprungbrettCourseAdmin;
use Sprungbrett\Bundle\CourseBundle\Controller\CourseController;
use Sprungbrett\Bundle\CourseBundle\DependencyInjection\SprungbrettCourseExtension;
use Sprungbrett\Bundle\CourseBundle\Model\Course\Handler\CreateCourseHandler;
use Sprungbrett\Bundle\CourseBundle\Model\Course\Handler\FindCourseHandler;
use Sprungbrett\Bundle\CourseBundle\Model\Course\Handler\ListCourseQueryHandler;
use Sprungbrett\Bundle\CourseBundle\Model\Course\Handler\ModifyCourseHandler;
use Sprungbrett\Bundle\CourseBundle\Model\Course\Handler\RemoveCourseHandler;
use Sulu\Bundle\AdminBundle\DependencyInjection\SuluAdminExtension;
use Sulu\Bundle\CoreBundle\DependencyInjection\SuluCoreExtension;
use Sulu\Bundle\RouteBundle\DependencyInjection\SuluRouteExtension;
use Sulu\Component\HttpKernel\SuluKernel;
use Symfony\Bundle\FrameworkBundle\DependencyInjection\FrameworkExtension;
use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;

class SprungbrettCourseExtensionTest extends AbstractExtensionTestCase
{
    protected function setUp()
    {
        parent::setUp();

        $this->container->prependExtensionConfig(
            'sulu_admin',
            [
                'email' => 'test@sprungbrett.io',
                'field_type_options' => [],
            ]
        );

        $this->container->prependExtensionConfig(
            'sulu_core',
            [
                'content' => [
                    'structure' => [
                        'default_type' => [
                            'snippet' => 'default',
                        ],
                        'paths' => [],
                    ],
                ],
            ]
        );

        $this->container->prependExtensionConfig(
            'jms_serializer',
            [
                'metadata' => [
                    'debug' => false,
                ],
            ]
        );

        $this->container->setParameter('sulu.context', SuluKernel::CONTEXT_ADMIN);
        $this->container->setParameter('kernel.debug', false);
        $this->container->setParameter('kernel.bundles', []);
        $this->container->setParameter('kernel.cache_dir', __DIR__);
        $this->container->setParameter('kernel.project_dir', __DIR__);
        $this->container->setParameter('kernel.root_dir', __DIR__);
        $this->container->setParameter('kernel.bundles_metadata', []);
    }

    protected function getContainerExtensions()
    {
        return [
            new FrameworkExtension(),
            new DoctrineExtension(),
            new JMSSerializerExtension(),
            new SuluCoreExtension(),
            new SuluAdminExtension(),
            new SuluRouteExtension(),
            new SprungbrettCourseExtension(),
        ];
    }

    public function testPrependedConfigAdmin()
    {
        $this->load();

        $config = $this->container->getExtensionConfig('jms_serializer');
        $this->assertArrayHasKey('metadata', $config[0]);

        $config = $this->container->getExtensionConfig('sulu_admin');
        $this->assertCount(2, $config);
        $this->assertArrayHasKey('resources', $config[0]);

        $config = $this->container->getExtensionConfig('sulu_route');
        $this->assertCount(1, $config);
        $this->assertArrayHasKey('mappings', $config[0]);

        $config = $this->container->getExtensionConfig('sulu_core');
        $this->assertCount(3, $config);
        $this->assertArrayHasKey('content', $config[0]);
    }

    public function testPrependedConfigWebsite()
    {
        $this->container->setParameter('sulu.context', SuluKernel::CONTEXT_WEBSITE);
        $this->load();

        $config = $this->container->getExtensionConfig('jms_serializer');
        $this->assertArrayHasKey('metadata', $config[0]);

        $config = $this->container->getExtensionConfig('sulu_admin');
        $this->assertCount(1, $config);
    }

    public function testRegisterServices()
    {
        $this->load();

        $this->assertTrue($this->container->has(CreateCourseHandler::class));
        $this->assertTrue($this->container->has(ModifyCourseHandler::class));
        $this->assertTrue($this->container->has(RemoveCourseHandler::class));
        $this->assertTrue($this->container->has(FindCourseHandler::class));
        $this->assertTrue($this->container->has(ListCourseQueryHandler::class));

        $this->assertTrue($this->container->has(SprungbrettCourseAdmin::class));
        $this->assertTrue($this->container->getDefinition(SprungbrettCourseAdmin::class)->hasTag('sulu.admin'));
        $this->assertTrue($this->container->has(CourseController::class));
    }

    protected function load(array $configurationValues = [])
    {
        $this->container->prependExtensionConfig('sprungbrett_course', $this->getMinimalConfiguration());
        $this->container->prependExtensionConfig('sprungbrett_course', $configurationValues);

        foreach ($this->container->getExtensions() as $extension) {
            if ($extension instanceof PrependExtensionInterface) {
                $extension->prepend($this->container);
            }

            $extension->load($this->container->getExtensionConfig($extension->getAlias()), $this->container);
        }
    }
}
