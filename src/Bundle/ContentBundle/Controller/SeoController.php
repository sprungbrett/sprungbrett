<?php

declare(strict_types=1);

namespace Sprungbrett\Bundle\ContentBundle\Controller;

use FOS\RestBundle\Controller\ControllerTrait;
use FOS\RestBundle\Routing\ClassResourceInterface;
use FOS\RestBundle\View\ViewHandlerInterface;
use Sprungbrett\Bundle\ContentBundle\Model\Seo\Message\ModifySeoMessage;
use Sprungbrett\Bundle\ContentBundle\Model\Seo\Query\FindSeoQuery;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Messenger\MessageBusInterface;

abstract class SeoController implements ClassResourceInterface
{
    use ControllerTrait;

    /**
     * @var MessageBusInterface
     */
    protected $messageBus;

    /**
     * @var string
     */
    protected $resourceKey;

    public function __construct(MessageBusInterface $messageBus, ViewHandlerInterface $viewHandler)
    {
        $this->messageBus = $messageBus;

        $this->setViewHandler($viewHandler);
    }

    public function cgetAction(): Response
    {
        throw new NotFoundHttpException();
    }

    public function getAction(Request $request, string $resourceId): Response
    {
        $content = $this->messageBus->dispatch(
            new FindSeoQuery($this->getResourceKey(), $resourceId, $request->query->get('locale'))
        );

        return $this->handleView($this->view($content));
    }

    public function putAction(Request $request, string $resourceId): Response
    {
        $locale = $request->query->get('locale');
        $this->messageBus->dispatch(
            new ModifySeoMessage($this->getResourceKey(), $resourceId, $locale, $request->request->all())
        );

        $action = $request->query->get('action');
        if ($action) {
            $this->handleAction($resourceId, $locale, $action);
        }

        $content = $this->messageBus->dispatch(
            new FindSeoQuery($this->getResourceKey(), $resourceId, $request->query->get('locale'))
        );

        return $this->handleView($this->view($content));
    }

    protected function handleAction(string $resourceId, string $locale, string $action): void
    {
        if ('publish' === $action) {
            $this->handlePublish($resourceId, $locale);
        }
    }

    abstract protected function handlePublish(string $resourceId, string $locale): void;

    abstract protected function getResourceKey(): string;
}
