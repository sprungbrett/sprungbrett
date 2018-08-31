<?php

namespace Sprungbrett\Component\Resource\Controller;

use FOS\RestBundle\View\View;
use FOS\RestBundle\View\ViewHandlerInterface;
use League\Tactician\CommandBus;
use Sprungbrett\Component\Resource\Model\Command\CommandFactoryInterface;
use Sulu\Component\Rest\RequestParametersTrait;
use Sulu\Component\Security\Authentication\UserInterface;
use Sulu\Component\Security\SecuredControllerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class ResourceController implements SecuredControllerInterface
{
    use RequestParametersTrait;

    /**
     * @var CommandFactoryInterface
     */
    private $commandFactory;

    /**
     * @var CommandBus
     */
    private $commandBus;

    /**
     * @var ViewHandlerInterface
     */
    private $viewHandler;

    /**
     * @var string
     */
    private $entityClass;

    /**
     * @var string
     */
    private $resourceKey;

    /**
     * @var string
     */
    private $securityContext;

    /**
     * @var string
     */
    private $defaultLocale;

    /**
     * @var TokenStorageInterface|null
     */
    private $tokenStorage;

    public function __construct(
        CommandFactoryInterface $commandFactory,
        CommandBus $commandBus,
        ViewHandlerInterface $viewHandler,
        string $entityClass,
        string $resourceKey,
        string $securityContext,
        string $defaultLocale,
        TokenStorageInterface $tokenStorage = null
    ) {
        $this->commandFactory = $commandFactory;
        $this->commandBus = $commandBus;
        $this->viewHandler = $viewHandler;
        $this->entityClass = $entityClass;
        $this->resourceKey = $resourceKey;
        $this->securityContext = $securityContext;
        $this->defaultLocale = $defaultLocale;
        $this->tokenStorage = $tokenStorage;
    }

    public function cgetAction(Request $request): Response
    {
        $command = $this->commandFactory->createListQuery(
            $this->entityClass,
            $this->resourceKey,
            $this->getLocale($request),
            $request->get('_route'),
            $request->query->all()
        );

        return $this->handleView(View::create($this->commandBus->handle($command)));
    }

    public function getAction(Request $request, string $id): Response
    {
        $command = $this->commandFactory->createFindQuery($id, $this->getLocale($request));

        return $this->handleView(View::create($this->commandBus->handle($command)));
    }

    public function postAction(Request $request): Response
    {
        $locale = $this->getLocale($request);
        $action = $request->query->get('action');

        $command = $this->commandFactory->createCreateCommand($locale, $request->request->all());
        $resource = $this->commandBus->handle($command);

        if ($action) {
            $this->handleActionParameter($action, $resource->getId(), $this->getLocale($request));
        }

        return $this->handleView(View::create($resource));
    }

    public function putAction(Request $request, string $id): Response
    {
        $locale = $this->getLocale($request);
        $action = $request->query->get('action');

        $command = $this->commandFactory->createModifyCommand($id, $locale, $request->request->all());
        $resource = $this->commandBus->handle($command);

        if ($action) {
            $this->handleActionParameter($action, $id, $this->getLocale($request));
        }

        return $this->handleView(View::create($resource));
    }

    public function deleteAction(string $id): Response
    {
        $command = $this->commandFactory->createRemoveCommand($id);
        $this->commandBus->handle($command);

        return $this->handleView(View::create(null));
    }

    protected function handleActionParameter(string $action, string $id, string $locale): void
    {
        $actionCommands = $this->commandFactory->getActionCommands($id, $locale);
        if (array_key_exists($action, $actionCommands)) {
            $this->commandBus->handle($actionCommands[$action]);
        }
    }

    public function getSecurityContext()
    {
        return $this->securityContext;
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
        if (!$this->tokenStorage) {
            throw new AccessDeniedHttpException();
        }

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
