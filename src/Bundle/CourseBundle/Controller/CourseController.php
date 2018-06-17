<?php

namespace Sprungbrett\Bundle\CourseBundle\Controller;

use FOS\RestBundle\Controller\Annotations\NamePrefix;
use FOS\RestBundle\Routing\ClassResourceInterface;
use FOS\RestBundle\View\View;
use FOS\RestBundle\View\ViewHandlerInterface;
use League\Tactician\CommandBus;
use Sprungbrett\Bundle\CourseBundle\Admin\SprungbrettCourseAdmin;
use Sprungbrett\Bundle\CourseBundle\Model\Query\ListCourseQuery;
use Sprungbrett\Component\Course\Model\Command\CreateCourseCommand;
use Sprungbrett\Component\Course\Model\Command\ModifyCourseCommand;
use Sprungbrett\Component\Course\Model\Command\RemoveCourseCommand;
use Sprungbrett\Component\Course\Model\Query\FindCourseQuery;
use Sulu\Component\Rest\RequestParametersTrait;
use Sulu\Component\Security\Authentication\UserInterface;
use Sulu\Component\Security\SecuredControllerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

/**
 * @NamePrefix("sprungbrett.")
 */
class CourseController implements SecuredControllerInterface, ClassResourceInterface
{
    const RESOURCE_KEY = 'courses';

    use RequestParametersTrait;

    /**
     * @var string
     */
    private $entityClass;

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
        string $entityClass,
        CommandBus $commandBus,
        ViewHandlerInterface $viewHandler,
        TokenStorageInterface $tokenStorage,
        string $defaultLocale
    ) {
        $this->entityClass = $entityClass;
        $this->commandBus = $commandBus;
        $this->viewHandler = $viewHandler;
        $this->tokenStorage = $tokenStorage;
        $this->defaultLocale = $defaultLocale;
    }

    public function cgetAction(Request $request): Response
    {
        $command = new ListCourseQuery(
            $this->entityClass,
            self::RESOURCE_KEY,
            $this->getLocale($request),
            $request->get('_route'),
            $request->query->all()
        );

        return $this->handleView(View::create($this->commandBus->handle($command)));
    }

    public function getAction(Request $request, string $id): Response
    {
        $command = new FindCourseQuery($id, $this->getLocale($request));

        return $this->handleView(View::create($this->commandBus->handle($command)));
    }

    public function postAction(Request $request): Response
    {
        $command = new CreateCourseCommand($this->getLocale($request), $request->request->all());

        return $this->handleView(View::create($this->commandBus->handle($command)));
    }

    public function putAction(Request $request, string $id): Response
    {
        $command = new ModifyCourseCommand($id, $this->getLocale($request), $request->request->all());

        return $this->handleView(View::create($this->commandBus->handle($command)));
    }

    public function deleteAction(string $id): Response
    {
        $command = new RemoveCourseCommand($id);
        $this->commandBus->handle($command);

        return $this->handleView(View::create(null));
    }

    public function getSecurityContext()
    {
        return SprungbrettCourseAdmin::SECURITY_CONTEXT;
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
