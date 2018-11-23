<?php

declare(strict_types=1);

namespace Sprungbrett\Bundle\CourseBundle\Controller;

use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\ControllerTrait;
use FOS\RestBundle\Routing\ClassResourceInterface;
use FOS\RestBundle\View\ViewHandlerInterface;
use Sprungbrett\Bundle\CourseBundle\Admin\ScheduleAdmin;
use Sprungbrett\Bundle\CourseBundle\Model\Schedule\Message\CreateScheduleMessage;
use Sprungbrett\Bundle\CourseBundle\Model\Schedule\Message\ModifyScheduleMessage;
use Sprungbrett\Bundle\CourseBundle\Model\Schedule\Message\PublishScheduleMessage;
use Sprungbrett\Bundle\CourseBundle\Model\Schedule\Message\RemoveScheduleMessage;
use Sprungbrett\Bundle\CourseBundle\Model\Schedule\Query\FindScheduleQuery;
use Sprungbrett\Bundle\CourseBundle\Model\Schedule\Query\ListSchedulesQuery;
use Sulu\Component\Security\SecuredControllerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;

/**
 * @Rest\NamePrefix("sprungbrett.")
 */
class ScheduleController implements ClassResourceInterface, SecuredControllerInterface
{
    use ControllerTrait;

    /**
     * @var MessageBusInterface
     */
    private $messageBus;

    public function __construct(MessageBusInterface $messageBus, ViewHandlerInterface $viewHandler)
    {
        $this->messageBus = $messageBus;

        $this->setViewHandler($viewHandler);
    }

    public function cgetAction(Request $request): Response
    {
        $courses = $this->messageBus->dispatch(
            new ListSchedulesQuery(
                $request->get('locale'),
                $request->get('_route'),
                $request->request->all()
            )
        );

        return $this->handleView($this->view($courses));
    }

    public function postAction(Request $request): Response
    {
        $message = new CreateScheduleMessage($request->get('locale'), $request->request->all());
        $this->messageBus->dispatch($message);

        $action = $request->query->get('action');
        if ($action) {
            $this->handleAction($message->getUuid(), $message->getLocale(), $action);
        }

        $course = $this->messageBus->dispatch(new FindScheduleQuery($message->getUuid(), $message->getLocale()));

        return $this->handleView($this->view($course, 201));
    }

    public function getAction(string $uuid, Request $request): Response
    {
        $course = $this->messageBus->dispatch(new FindScheduleQuery($uuid, $request->get('locale')));

        return $this->handleView($this->view($course));
    }

    public function putAction(string $uuid, Request $request): Response
    {
        $message = new ModifyScheduleMessage($uuid, $request->get('locale'), $request->request->all());
        $this->messageBus->dispatch($message);

        $action = $request->query->get('action');
        if ($action) {
            $this->handleAction($message->getUuid(), $message->getLocale(), $action);
        }

        $course = $this->messageBus->dispatch(new FindScheduleQuery($message->getUuid(), $message->getLocale()));

        return $this->handleView($this->view($course));
    }

    public function deleteAction(string $uuid): Response
    {
        $message = new RemoveScheduleMessage($uuid);
        $this->messageBus->dispatch($message);

        return $this->handleView($this->view());
    }

    protected function handleAction(string $uuid, string $locale, string $action): void
    {
        if ('publish' === $action) {
            $this->messageBus->dispatch(new PublishScheduleMessage($uuid, $locale));
        }
    }

    public function getSecurityContext()
    {
        return ScheduleAdmin::SECURITY_CONTEXT;
    }

    public function getLocale(Request $request)
    {
        return $request->get('locale');
    }
}
