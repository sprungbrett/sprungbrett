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
use Sulu\Component\Security\SecuredControllerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @NamePrefix("sprungbrett.")
 */
class CourseController implements SecuredControllerInterface, ClassResourceInterface
{
    const RESOURCE_KEY = 'courses';

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

    public function __construct(string $entityClass, CommandBus $commandBus, ViewHandlerInterface $viewHandler)
    {
        $this->entityClass = $entityClass;
        $this->commandBus = $commandBus;
        $this->viewHandler = $viewHandler;
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

    public function getAction(string $id): Response
    {
        $command = new FindCourseQuery($id);

        return $this->handleView(View::create($this->commandBus->handle($command)));
    }

    public function postAction(Request $request): Response
    {
        $command = new CreateCourseCommand($request->request->all());

        return $this->handleView(View::create($this->commandBus->handle($command)));
    }

    public function putAction(Request $request, string $id): Response
    {
        $command = new ModifyCourseCommand($id, $request->request->all());

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

    public function getLocale(Request $request): ?string
    {
        return null;
    }

    protected function handleView(View $view): Response
    {
        return $this->viewHandler->handle($view);
    }
}
