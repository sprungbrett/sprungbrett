<?php

namespace Sprungbrett\Bundle\CourseBundle\Controller;

use JMS\Serializer\SerializationContext;
use JMS\Serializer\SerializerInterface;
use Sprungbrett\Bundle\CourseBundle\Model\Course\CourseInterface;
use Sulu\Bundle\HttpCacheBundle\Cache\AbstractHttpCache;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Templating\EngineInterface;

class WebsiteCourseController
{
    /**
     * @var SerializerInterface
     */
    private $serializer;

    /**
     * @var EngineInterface
     */
    private $templating;

    /**
     * @var int
     */
    private $maxAge;

    /**
     * @var int
     */
    private $sharedMaxAge;

    public function __construct(
        SerializerInterface $serializer,
        EngineInterface $templating,
        int $maxAge,
        int $sharedMaxAge
    ) {
        $this->serializer = $serializer;
        $this->templating = $templating;
        $this->maxAge = $maxAge;
        $this->sharedMaxAge = $sharedMaxAge;
    }

    public function indexAction(Request $request, CourseInterface $object, string $view): Response
    {
        $parameters = $this->serializer->serialize(
            $object,
            'array',
            SerializationContext::create()->setGroups(['website'])
        );

        return $this->createResponse($request)
            ->setContent($this->templating->render($view . '.' . $request->getRequestFormat() . '.twig', $parameters));
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
