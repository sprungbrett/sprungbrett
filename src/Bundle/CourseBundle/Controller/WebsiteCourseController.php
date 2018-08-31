<?php

namespace Sprungbrett\Bundle\CourseBundle\Controller;

use Sprungbrett\Bundle\CourseBundle\Model\Course\CourseInterface;
use Sprungbrett\Component\Content\Resolver\ContentResolverInterface;
use Sulu\Bundle\HttpCacheBundle\Cache\AbstractHttpCache;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Templating\EngineInterface;

class WebsiteCourseController
{
    /**
     * @var ContentResolverInterface
     */
    private $contentResolver;

    /**
     * @var EngineInterface
     */
    private $engine;

    /**
     * @var int
     */
    private $maxAge;

    /**
     * @var int
     */
    private $sharedMaxAge;

    public function __construct(
        ContentResolverInterface $contentResolver,
        EngineInterface $engine,
        int $maxAge,
        int $sharedMaxAge
    ) {
        $this->engine = $engine;
        $this->maxAge = $maxAge;
        $this->sharedMaxAge = $sharedMaxAge;
        $this->contentResolver = $contentResolver;
    }

    public function indexAction(Request $request, CourseInterface $object, string $view): Response
    {
        $requestFormat = $request->getRequestFormat();
        $view = $view . '.' . $requestFormat . '.twig';

        $parameters = $this->contentResolver->resolve($object);
        $parameters['course'] = $object;

        return $this->render($view, $parameters, $this->createResponse($request));
    }

    protected function render($view, array $parameters = [], Response $response = null)
    {
        if (null === $response) {
            $response = new Response();
        }

        return $response->setContent($this->engine->render($view, $parameters));
    }

    protected function createResponse(Request $request): Response
    {
        $response = new Response();
        $cacheLifetime = $request->get('_cacheLifetime');
        if ($cacheLifetime) {
            $response->setPublic();
            $response->headers->set(
                AbstractHttpCache::HEADER_REVERSE_PROXY_TTL,
                $cacheLifetime
            );
            $response->setMaxAge($this->maxAge);
            $response->setSharedMaxAge($this->sharedMaxAge);
        }

        return $response;
    }
}
