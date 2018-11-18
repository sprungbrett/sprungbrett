<?php

declare(strict_types=1);

namespace Sprungbrett\Bundle\PortalBundle\Controller;

use Sprungbrett\Bundle\PortalBundle\Model\CourseView\CourseViewInterface;
use Sulu\Bundle\HttpCacheBundle\Cache\SuluHttpCache;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class CourseController extends Controller
{
    use CourseViewTrait;

    public function indexAction(Request $request, CourseViewInterface $object, string $view): Response
    {
        return $this->renderCourse($request, $object, $view);
    }

    protected function renderCourse(
        Request $request,
        CourseViewInterface $object,
        string $view,
        array $attributes = []
    ): Response {
        $view = $view . '.' . $request->getRequestFormat() . '.twig';

        return $this->render(
            $view,
            array_merge($attributes, $this->resolveCourse($object)),
            $this->createResponse($request)
        );
    }

    private function createResponse(Request $request)
    {
        $response = new Response();
        $cacheLifetime = $request->attributes->get('_cacheLifetime');

        if ($cacheLifetime) {
            $response->setPublic();
            $response->headers->set(
                SuluHttpCache::HEADER_REVERSE_PROXY_TTL,
                $cacheLifetime
            );

            $response->setMaxAge($this->container->getParameter('sulu_http_cache.cache.max_age'));
            $response->setSharedMaxAge($this->container->getParameter('sulu_http_cache.cache.shared_max_age'));
        }

        return $response;
    }
}
