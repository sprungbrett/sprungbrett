<?php

namespace Sprungbrett\Bundle\CourseBundle\Tests\Unit\DependencyInjection;

use JMS\SerializerBundle\DependencyInjection\JMSSerializerExtension;
use Matthias\SymfonyDependencyInjectionTest\PhpUnit\AbstractExtensionTestCase;
use Sprungbrett\Bundle\CourseBundle\Admin\SprungbrettCourseAdmin;
use Sprungbrett\Bundle\CourseBundle\Controller\CourseController;
use Sprungbrett\Bundle\CourseBundle\DependencyInjection\SprungbrettCourseExtension;
use Sprungbrett\Bundle\CourseBundle\Model\Handler\ListCourseQueryHandler;
use Sprungbrett\Component\Course\Model\Handler\CreateCourseHandler;
use Sprungbrett\Component\Course\Model\Handler\FindCourseHandler;
use Sprungbrett\Component\Course\Model\Handler\ModifyCourseHandler;
use Sprungbrett\Component\Course\Model\Handler\RemoveCourseHandler;
use Sulu\Bundle\AdminBundle\DependencyInjection\SuluAdminExtension;
use Sulu\Component\HttpKernel\SuluKernel;
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

        $this->container->setParameter('sulu.context', SuluKernel::CONTEXT_ADMIN);
        $this->container->setParameter('kernel.debug', false);
        $this->container->setParameter('kernel.bundles', []);
        $this->container->setParameter('kernel.cache_dir', __DIR__);
    }

    protected function getContainerExtensions()
    {
        return [
            new SuluAdminExtension(),
            new JMSSerializerExtension(),
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
