<?php

namespace Sprungbrett\Bundle\CourseBundle\Controller;

use League\Tactician\CommandBus;
use Sprungbrett\Bundle\CourseBundle\Model\Attendee\Command\ShowInterestCommand;
use Sprungbrett\Bundle\CourseBundle\Model\Attendee\CourseAttendeeInterface;
use Sprungbrett\Bundle\CourseBundle\Model\Attendee\Query\CountAttendeeQuery;
use Sprungbrett\Bundle\CourseBundle\Model\Attendee\Query\IsAttendeeQuery;
use Sulu\Bundle\HttpCacheBundle\Cache\AbstractHttpCache;
use Sulu\Bundle\SecurityBundle\Entity\User;
use Sulu\Component\Security\Authentication\UserInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Templating\EngineInterface;

class WebsiteInterestController
{
    /**
     * @var CommandBus
     */
    private $commandBus;

    /**
     * @var EngineInterface
     */
    private $engine;

    /**
     * @var TokenStorageInterface
     */
    private $tokenStorage;

    public function __construct(CommandBus $commandBus, EngineInterface $engine, TokenStorageInterface $tokenStorage)
    {
        $this->commandBus = $commandBus;
        $this->engine = $engine;
        $this->tokenStorage = $tokenStorage;
    }

    public function createAction(Request $request, string $courseId): Response
    {
        /** @var User $user */
        $user = $this->getUser();

        /** @var CourseAttendeeInterface $courseAttendee */
        $courseAttendee = $this->commandBus->handle(
            new ShowInterestCommand($user->getContact()->getId(), $courseId, $request->getLocale())
        );

        return $this->makePrivate(new RedirectResponse($courseAttendee->getCourse()->getRoutePath() . '?success=1'));
    }

    public function renderAction(string $courseId): Response
    {
        /** @var User $user */
        $user = $this->getUser();

        $hasInterest = $this->commandBus->handle(new IsAttendeeQuery($user->getContact()->getId(), $courseId));
        $count = $this->commandBus->handle(new CountAttendeeQuery($courseId));

        return $this->makePrivate(
            $this->render(
                '@SprungbrettCourse/Interest/index.html.twig',
                ['count' => $count, 'hasInterest' => $hasInterest, 'id' => $courseId]
            )
        );
    }

    protected function getUser(): UserInterface
    {
        $token = $this->tokenStorage->getToken();
        if (!$token) {
            throw new AccessDeniedHttpException();
        }

        $user = $token->getUser();
        if (!$user instanceof UserInterface) {
            throw new AccessDeniedHttpException();
        }

        return $user;
    }

    protected function render($view, array $parameters = [], Response $response = null)
    {
        if (null === $response) {
            $response = new Response();
        }

        return $response->setContent($this->engine->render($view, $parameters));
    }

    protected function makePrivate(?Response $response = null): Response
    {
        if (!$response) {
            $response = new Response();
        }

        $response->setPrivate();
        $response->setMaxAge(0);
        $response->setSharedMaxAge(0);
        $response->headers->addCacheControlDirective('must-revalidate', true);
        $response->headers->addCacheControlDirective('no-store', true);
        $response->headers->set(AbstractHttpCache::HEADER_REVERSE_PROXY_TTL, '0');

        return $response;
    }
}
