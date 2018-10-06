<?php

namespace Sprungbrett\Bundle\CourseBundle\Tests\Unit\Controller;

use PHPUnit\Framework\TestCase;
use Sprungbrett\Bundle\CourseBundle\Controller\WebsiteCourseController;
use Sprungbrett\Bundle\CourseBundle\Model\Course\CourseInterface;
use Sprungbrett\Component\Content\Resolver\ContentResolverInterface;
use Sulu\Bundle\HttpCacheBundle\Cache\SuluHttpCache;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Templating\EngineInterface;

class WebsiteCourseControllerTest extends TestCase
{
    public function testIndexAction()
    {
        $contentResolver = $this->prophesize(ContentResolverInterface::class);
        $templating = $this->prophesize(EngineInterface::class);
        $controller = new WebsiteCourseController($contentResolver->reveal(), $templating->reveal(), 1800, 3600);

        $course = $this->prophesize(CourseInterface::class);

        $contentResolver->resolve($course->reveal())->willReturn(
                ['view' => ['title' => []], 'content' => ['title' => 'Sprungbrett is awesome']]
            );

        $templating->render(
            '@SprungbrettCourse/Course/index.html.twig',
            [
                'view' => ['title' => []],
                'content' => ['title' => 'Sprungbrett is awesome'],
                'course' => $course->reveal(),
            ]
        )->willReturn('<h1>Hello world</h1>');

        $request = $this->prophesize(Request::class);
        $request->getRequestFormat()->willReturn('html');
        $request->get('_cacheLifetime')->willReturn(7200);

        $response = $controller->indexAction($request->reveal(), $course->reveal(), '@SprungbrettCourse/Course/index');

        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals('<h1>Hello world</h1>', $response->getContent());
        $this->assertTrue($response->headers->getCacheControlDirective('public'));
        $this->assertEquals(7200, $response->headers->get(SuluHttpCache::HEADER_REVERSE_PROXY_TTL));
        $this->assertEquals(1800, $response->headers->getCacheControlDirective('max-age'));
        $this->assertEquals(3600, $response->headers->getCacheControlDirective('s-maxage'));
    }

    public function testIndexActionNoCache()
    {
        $contentResolver = $this->prophesize(ContentResolverInterface::class);
        $templating = $this->prophesize(EngineInterface::class);
        $controller = new WebsiteCourseController($contentResolver->reveal(), $templating->reveal(), 1800, 3600);

        $course = $this->prophesize(CourseInterface::class);

        $contentResolver->resolve($course->reveal())->willReturn(
            ['view' => ['title' => []], 'content' => ['title' => 'Sprungbrett is awesome']]
        );

        $templating->render(
            '@SprungbrettCourse/Course/index.html.twig',
            [
                'view' => ['title' => []],
                'content' => ['title' => 'Sprungbrett is awesome'],
                'course' => $course->reveal(),
            ]
        )->willReturn('<h1>Hello world</h1>');

        $request = $this->prophesize(Request::class);
        $request->getRequestFormat()->willReturn('html');
        $request->get('_cacheLifetime')->willReturn(null);

        $response = $controller->indexAction($request->reveal(), $course->reveal(), '@SprungbrettCourse/Course/index');

        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals('<h1>Hello world</h1>', $response->getContent());
        $this->assertNull($response->headers->getCacheControlDirective('public'));
    }
}
