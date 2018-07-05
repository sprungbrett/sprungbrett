<?php

namespace Sprungbrett\Bundle\CourseBundle\Controller;

use FOS\RestBundle\Controller\Annotations\NamePrefix;
use FOS\RestBundle\Routing\ClassResourceInterface;
use FOS\RestBundle\View\View;
use FOS\RestBundle\View\ViewHandlerInterface;
use League\Tactician\CommandBus;
use Sprungbrett\Bundle\CourseBundle\Admin\SprungbrettCourseAdmin;
use Sprungbrett\Bundle\CourseBundle\Model\Attendee\Command\ModifyAttendeeCommand;
use Sprungbrett\Bundle\CourseBundle\Model\Attendee\Query\FindAttendeeQuery;
use Sulu\Component\Rest\RequestParametersTrait;
use Sulu\Component\Security\Authentication\UserInterface;
use Sulu\Component\Security\SecuredControllerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

/**
 * @NamePrefix("sprungbrett.")
 */
class AttendeeController implements SecuredControllerInterface, ClassResourceInterface
{
    use RequestParametersTrait;

    /**
     * @var CommandBus
     */
    private $commandBus;

    /**
     * @var ViewHandlerInterface
     */
    private $viewHandler;

    /**
     * @var TokenStorageInterface
     */
    private $tokenStorage;

    /**
     * @var string
     */
    private $defaultLocale;

    public function __construct(
        CommandBus $commandBus,
        ViewHandlerInterface $viewHandler,
        TokenStorageInterface $tokenStorage,
        string $defaultLocale
    ) {
        $this->commandBus = $commandBus;
        $this->viewHandler = $viewHandler;
        $this->tokenStorage = $tokenStorage;
        $this->defaultLocale = $defaultLocale;
    }

    public function cgetAction(): Response
    {
        throw new NotFoundHttpException();
    }

    public function getAction(Request $request, int $id): Response
    {
        $command = new FindAttendeeQuery($id, $this->getLocale($request));

        return $this->handleView(View::create($this->commandBus->handle($command)));
    }

    public function putAction(Request $request, int $id): Response
    {
        $command = new ModifyAttendeeCommand($id, $this->getLocale($request), $request->request->all());

        return $this->handleView(View::create($this->commandBus->handle($command)));
    }

    public function getSecurityContext()
    {
        return SprungbrettCourseAdmin::ATTENDEE_SECURITY_CONTEXT;
    }

    public function getLocale(Request $request): string
    {
        $locale = $this->getRequestParameter($request, 'locale', false);
        if ($locale) {
            return $locale;
        }

        if (!$this->tokenStorage) {
            return $this->defaultLocale;
        }

        return $this->getUser()->getLocale();
    }

    protected function handleView(View $view): Response
    {
        return $this->viewHandler->handle($view);
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
}
