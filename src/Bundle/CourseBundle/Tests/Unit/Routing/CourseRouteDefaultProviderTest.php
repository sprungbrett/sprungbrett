<?php

namespace Sprungbrett\Bundle\CourseBundle\Tests\Unit\Routing;

use League\Tactician\CommandBus;
use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Sprungbrett\Bundle\CourseBundle\Controller\WebsiteCourseController;
use Sprungbrett\Bundle\CourseBundle\Model\Course\Course;
use Sprungbrett\Bundle\CourseBundle\Model\Course\CourseInterface;
use Sprungbrett\Bundle\CourseBundle\Model\Course\Query\FindCourseQuery;
use Sprungbrett\Bundle\CourseBundle\Routing\CourseRouteDefaultProvider;
use Sprungbrett\Bundle\CourseBundle\Structure\CourseBridge;
use Sulu\Bundle\HttpCacheBundle\CacheLifetime\CacheLifetimeResolverInterface;
use Sulu\Component\Content\Compat\StructureManagerInterface;
use Sulu\Component\Content\Metadata\Factory\StructureMetadataFactoryInterface;
use Sulu\Component\Content\Metadata\StructureMetadata;

class CourseRouteDefaultProviderTest extends TestCase
{
    public function testGetByEntity()
    {
        $commandBus = $this->prophesize(CommandBus::class);
        $structureMetadataFactory = $this->prophesize(StructureMetadataFactoryInterface::class);
        $structureManager = $this->prophesize(StructureManagerInterface::class);
        $cacheLifetimeResolver = $this->prophesize(CacheLifetimeResolverInterface::class);

        $provider = new CourseRouteDefaultProvider(
            $commandBus->reveal(),
            $structureMetadataFactory->reveal(),
            $structureManager->reveal(),
            $cacheLifetimeResolver->reveal()
        );

        $course = $this->prophesize(CourseInterface::class);
        $course->getStructureType()->willReturn('default');
        $commandBus->handle(
            Argument::that(
                function (FindCourseQuery $query) {
                    return '123-123-123' === $query->getId()
                        && 'de' === $query->getLocalization()->getLocale();
                }
            )
        )->willReturn($course->reveal());

        $metadata = $this->prophesize(StructureMetadata::class);
        $metadata->getView()->willReturn('courses/default');
        $metadata->getController()->willReturn(WebsiteCourseController::class . ':indexAction');
        $metadata->getCacheLifetime()->willReturn(['type' => 'seconds', 'value' => 3600]);
        $structureMetadataFactory->getStructureMetadata('course', 'default')->willReturn($metadata->reveal());

        $cacheLifetimeResolver->resolve('seconds', 3600)->willReturn(3600);

        $structure = $this->prophesize(CourseBridge::class);
        $structure->setCourse($course->reveal())->shouldBeCalled();
        $structureManager->wrapStructure('course', $metadata->reveal())->willReturn($structure->reveal());

        $defaults = $provider->getByEntity(CourseInterface::class, '123-123-123', 'de');

        $this->assertEquals($course->reveal(), $defaults['object']);
        $this->assertEquals('courses/default', $defaults['view']);
        $this->assertEquals(WebsiteCourseController::class . ':indexAction', $defaults['_controller']);
        $this->assertEquals(3600, $defaults['_cacheLifetime']);
        $this->assertEquals($structure->reveal(), $defaults['structure']);
    }

    public function testGetByEntityWithObject()
    {
        $commandBus = $this->prophesize(CommandBus::class);
        $structureMetadataFactory = $this->prophesize(StructureMetadataFactoryInterface::class);
        $structureManager = $this->prophesize(StructureManagerInterface::class);
        $cacheLifetimeResolver = $this->prophesize(CacheLifetimeResolverInterface::class);

        $provider = new CourseRouteDefaultProvider(
            $commandBus->reveal(),
            $structureMetadataFactory->reveal(),
            $structureManager->reveal(),
            $cacheLifetimeResolver->reveal()
        );

        $course = $this->prophesize(CourseInterface::class);
        $course->getStructureType()->willReturn('default');
        $commandBus->handle(Argument::any())->shouldNotBeCalled();

        $metadata = $this->prophesize(StructureMetadata::class);
        $metadata->getView()->willReturn('courses/default');
        $metadata->getController()->willReturn(WebsiteCourseController::class . ':indexAction');
        $metadata->getCacheLifetime()->willReturn(['type' => 'seconds', 'value' => 3600]);
        $structureMetadataFactory->getStructureMetadata('course', 'default')->willReturn($metadata->reveal());

        $cacheLifetimeResolver->resolve('seconds', 3600)->willReturn(3600);

        $structure = $this->prophesize(CourseBridge::class);
        $structure->setCourse($course->reveal())->shouldBeCalled();
        $structureManager->wrapStructure('course', $metadata->reveal())->willReturn($structure->reveal());

        $defaults = $provider->getByEntity(CourseInterface::class, '123-123-123', 'de', $course->reveal());

        $this->assertEquals($course->reveal(), $defaults['object']);
        $this->assertEquals('courses/default', $defaults['view']);
        $this->assertEquals(WebsiteCourseController::class . ':indexAction', $defaults['_controller']);
        $this->assertEquals(3600, $defaults['_cacheLifetime']);
        $this->assertEquals($structure->reveal(), $defaults['structure']);
    }

    public function testIsPublished()
    {
        $commandBus = $this->prophesize(CommandBus::class);
        $structureMetadataFactory = $this->prophesize(StructureMetadataFactoryInterface::class);
        $structureManager = $this->prophesize(StructureManagerInterface::class);
        $cacheLifetimeResolver = $this->prophesize(CacheLifetimeResolverInterface::class);

        $provider = new CourseRouteDefaultProvider(
            $commandBus->reveal(),
            $structureMetadataFactory->reveal(),
            $structureManager->reveal(),
            $cacheLifetimeResolver->reveal()
        );

        $course = $this->prophesize(CourseInterface::class);
        $course->getWorkflowStage()->willReturn(CourseInterface::WORKFLOW_STAGE_PUBLISHED);
        $commandBus->handle(
            Argument::that(
                function (FindCourseQuery $query) {
                    return '123-123-123' === $query->getId()
                        && 'de' === $query->getLocalization()->getLocale();
                }
            )
        )->willReturn($course->reveal());

        $this->assertTrue($provider->isPublished(CourseInterface::class, '123-123-123', 'de'));
    }

    public function testIsNotPublished()
    {
        $commandBus = $this->prophesize(CommandBus::class);
        $structureMetadataFactory = $this->prophesize(StructureMetadataFactoryInterface::class);
        $structureManager = $this->prophesize(StructureManagerInterface::class);
        $cacheLifetimeResolver = $this->prophesize(CacheLifetimeResolverInterface::class);

        $provider = new CourseRouteDefaultProvider(
            $commandBus->reveal(),
            $structureMetadataFactory->reveal(),
            $structureManager->reveal(),
            $cacheLifetimeResolver->reveal()
        );

        $course = $this->prophesize(CourseInterface::class);
        $course->getWorkflowStage()->willReturn(CourseInterface::WORKFLOW_STAGE_TEST);
        $commandBus->handle(
            Argument::that(
                function (FindCourseQuery $query) {
                    return '123-123-123' === $query->getId()
                        && 'de' === $query->getLocalization()->getLocale();
                }
            )
        )->willReturn($course->reveal());

        $this->assertFalse($provider->isPublished(CourseInterface::class, '123-123-123', 'de'));
    }

    public function testSupports()
    {
        $commandBus = $this->prophesize(CommandBus::class);
        $structureMetadataFactory = $this->prophesize(StructureMetadataFactoryInterface::class);
        $structureManager = $this->prophesize(StructureManagerInterface::class);
        $cacheLifetimeResolver = $this->prophesize(CacheLifetimeResolverInterface::class);

        $provider = new CourseRouteDefaultProvider(
            $commandBus->reveal(),
            $structureMetadataFactory->reveal(),
            $structureManager->reveal(),
            $cacheLifetimeResolver->reveal()
        );

        $commandBus->handle(Argument::any())->shouldNotBeCalled();

        $this->assertTrue($provider->supports(Course::class));
    }

    public function testSupportsNot()
    {
        $commandBus = $this->prophesize(CommandBus::class);
        $structureMetadataFactory = $this->prophesize(StructureMetadataFactoryInterface::class);
        $structureManager = $this->prophesize(StructureManagerInterface::class);
        $cacheLifetimeResolver = $this->prophesize(CacheLifetimeResolverInterface::class);

        $provider = new CourseRouteDefaultProvider(
            $commandBus->reveal(),
            $structureMetadataFactory->reveal(),
            $structureManager->reveal(),
            $cacheLifetimeResolver->reveal()
        );

        $commandBus->handle(Argument::any())->shouldNotBeCalled();

        $this->assertFalse($provider->supports(\stdClass::class));
    }
}
