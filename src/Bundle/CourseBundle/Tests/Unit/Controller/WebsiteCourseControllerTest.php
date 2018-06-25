<?php

namespace Sprungbrett\Bundle\CourseBundle\Tests\Unit\Controller;

use PHPUnit\Framework\TestCase;
use Sprungbrett\Bundle\CourseBundle\Controller\WebsiteCourseController;
use Sprungbrett\Component\Course\Model\CourseInterface;
use Sulu\Bundle\HttpCacheBundle\Cache\AbstractHttpCache;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Templating\EngineInterface;

class WebsiteCourseControllerTest extends TestCase
{
    public function testIndexAction()
    {
        $templating = $this->prophesize(EngineInterface::class);
        $controller = new WebsiteCourseController($templating->reveal(), 1800, 3600);

        $course = $this->prophesize(CourseInterface::class);

        $templating->render('@SprungbrettCourse/Course/index.html.twig', ['course' => $course->reveal()])
            ->willReturn('<h1>Hello world</h1>');

        $request = $this->prophesize(Request::class);
        $request->getRequestFormat()->willReturn('html');
        $request->get('_cacheLifetime')->willReturn(7200);

        $response = $controller->indexAction($request->reveal(), $course->reveal(), '@SprungbrettCourse/Course/index');

        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals('<h1>Hello world</h1>', $response->getContent());
        $this->assertTrue($response->headers->getCacheControlDirective('public'));
        $this->assertEquals(7200, $response->headers->get(AbstractHttpCache::HEADER_REVERSE_PROXY_TTL));
        $this->assertEquals(1800, $response->headers->getCacheControlDirective('max-age'));
        $this->assertEquals(3600, $response->headers->getCacheControlDirective('s-maxage'));
    }

    public function testIndexActionNoCache()
    {
        $templating = $this->prophesize(EngineInterface::class);
        $controller = new WebsiteCourseController($templating->reveal(), 1800, 3600);

        $course = $this->prophesize(CourseInterface::class);

        $templating->render('@SprungbrettCourse/Course/index.html.twig', ['course' => $course->reveal()])
            ->willReturn('<h1>Hello world</h1>');

        $request = $this->prophesize(Request::class);
        $request->getRequestFormat()->willReturn('html');
        $request->get('_cacheLifetime')->willReturn(null);

        $response = $controller->indexAction($request->reveal(), $course->reveal(), '@SprungbrettCourse/Course/index');

        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals('<h1>Hello world</h1>', $response->getContent());
        $this->assertNull($response->headers->getCacheControlDirective('public'));
    }
}
