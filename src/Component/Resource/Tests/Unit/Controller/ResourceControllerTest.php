<?php

namespace Sprungbrett\Component\Resource\Tests\Unit\Controller;

use FOS\RestBundle\View\View;
use FOS\RestBundle\View\ViewHandlerInterface;
use League\Tactician\CommandBus;
use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Sprungbrett\Component\Resource\Controller\ResourceController;
use Sprungbrett\Component\Resource\Model\Command\CommandFactoryInterface;
use Sprungbrett\Component\Resource\Model\Command\CreateCommandInterface;
use Sprungbrett\Component\Resource\Model\Command\FindQueryInterface;
use Sprungbrett\Component\Resource\Model\Command\ListQueryInterface;
use Sprungbrett\Component\Resource\Model\Command\ModifyCommandInterface;
use Sprungbrett\Component\Resource\Model\Command\RemoveCommandInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class ResourceControllerTest extends TestCase
{
    public function testCGetAction()
    {
        $commandFactory = $this->prophesize(CommandFactoryInterface::class);
        $commandBus = $this->prophesize(CommandBus::class);
        $viewHandler = $this->prophesize(ViewHandlerInterface::class);
        $tokenStorage = $this->prophesize(TokenStorageInterface::class);

        $controller = new ResourceController(
            $commandFactory->reveal(),
            $commandBus->reveal(),
            $viewHandler->reveal(),
            \stdClass::class,
            'resources',
            'sprungbrett.resources',
            'de',
            $tokenStorage->reveal()
        );

        $command = $this->prophesize(ListQueryInterface::class);
        $commandFactory->createListQuery(\stdClass::class, 'resources', 'de', 'app.route', ['locale' => 'de'])
            ->willReturn($command->reveal());

        $result = ['total' => 1];
        $commandBus->handle($command->reveal())->willReturn($result);

        $response = $this->prophesize(Response::class);
        $viewHandler->handle(
            Argument::that(
                function (View $view) use ($result) {
                    return $view->getData() === $result;
                }
            )
        )->willReturn($response->reveal());

        $request = new Request(['locale' => 'de'], [], ['_route' => 'app.route']);

        $this->assertEquals($response->reveal(), $controller->cgetAction($request));
    }

    public function testGetAction()
    {
        $commandFactory = $this->prophesize(CommandFactoryInterface::class);
        $commandBus = $this->prophesize(CommandBus::class);
        $viewHandler = $this->prophesize(ViewHandlerInterface::class);
        $tokenStorage = $this->prophesize(TokenStorageInterface::class);

        $controller = new ResourceController(
            $commandFactory->reveal(),
            $commandBus->reveal(),
            $viewHandler->reveal(),
            \stdClass::class,
            'resources',
            'sprungbrett.resources',
            'de',
            $tokenStorage->reveal()
        );

        $command = $this->prophesize(FindQueryInterface::class);
        $commandFactory->createFindQuery('123-123-123', 'de')
            ->willReturn($command->reveal());

        $result = new \stdClass();
        $commandBus->handle($command->reveal())->willReturn($result);

        $response = $this->prophesize(Response::class);
        $viewHandler->handle(
            Argument::that(
                function (View $view) use ($result) {
                    return $view->getData() === $result;
                }
            )
        )->willReturn($response->reveal());

        $request = new Request(['locale' => 'de']);

        $this->assertEquals($response->reveal(), $controller->getAction($request, '123-123-123'));
    }

    public function testPostAction()
    {
        $commandFactory = $this->prophesize(CommandFactoryInterface::class);
        $commandBus = $this->prophesize(CommandBus::class);
        $viewHandler = $this->prophesize(ViewHandlerInterface::class);
        $tokenStorage = $this->prophesize(TokenStorageInterface::class);

        $controller = new ResourceController(
            $commandFactory->reveal(),
            $commandBus->reveal(),
            $viewHandler->reveal(),
            \stdClass::class,
            'resources',
            'sprungbrett.resources',
            'de',
            $tokenStorage->reveal()
        );

        $command = $this->prophesize(CreateCommandInterface::class);
        $commandFactory->createCreateCommand('de', ['title' => 'Sprungbrett is awesome'])
            ->willReturn($command->reveal());

        $result = new \stdClass();
        $commandBus->handle($command->reveal())->willReturn($result);

        $response = $this->prophesize(Response::class);
        $viewHandler->handle(
            Argument::that(
                function (View $view) use ($result) {
                    return $view->getData() === $result;
                }
            )
        )->willReturn($response->reveal());

        $request = new Request(['locale' => 'de'], ['title' => 'Sprungbrett is awesome']);

        $this->assertEquals($response->reveal(), $controller->postAction($request));
    }

    public function testPutAction()
    {
        $commandFactory = $this->prophesize(CommandFactoryInterface::class);
        $commandBus = $this->prophesize(CommandBus::class);
        $viewHandler = $this->prophesize(ViewHandlerInterface::class);
        $tokenStorage = $this->prophesize(TokenStorageInterface::class);

        $controller = new ResourceController(
            $commandFactory->reveal(),
            $commandBus->reveal(),
            $viewHandler->reveal(),
            \stdClass::class,
            'resources',
            'sprungbrett.resources',
            'de',
            $tokenStorage->reveal()
        );

        $command = $this->prophesize(ModifyCommandInterface::class);
        $commandFactory->createModifyCommand('123-123-123', 'de', ['title' => 'Sprungbrett is awesome'])
            ->willReturn($command->reveal());

        $result = new \stdClass();
        $commandBus->handle($command->reveal())->willReturn($result);

        $response = $this->prophesize(Response::class);
        $viewHandler->handle(
            Argument::that(
                function (View $view) use ($result) {
                    return $view->getData() === $result;
                }
            )
        )->willReturn($response->reveal());

        $request = new Request(['locale' => 'de'], ['title' => 'Sprungbrett is awesome']);

        $this->assertEquals($response->reveal(), $controller->putAction($request, '123-123-123'));
    }

    public function testDeleteAction()
    {
        $commandFactory = $this->prophesize(CommandFactoryInterface::class);
        $commandBus = $this->prophesize(CommandBus::class);
        $viewHandler = $this->prophesize(ViewHandlerInterface::class);
        $tokenStorage = $this->prophesize(TokenStorageInterface::class);

        $controller = new ResourceController(
            $commandFactory->reveal(),
            $commandBus->reveal(),
            $viewHandler->reveal(),
            \stdClass::class,
            'resources',
            'sprungbrett.resources',
            'de',
            $tokenStorage->reveal()
        );

        $command = $this->prophesize(RemoveCommandInterface::class);
        $commandFactory->createRemoveCommand('123-123-123')
            ->willReturn($command->reveal());

        $commandBus->handle($command->reveal());

        $response = $this->prophesize(Response::class);
        $viewHandler->handle(
            Argument::that(
                function (View $view) {
                    return null === $view->getData();
                }
            )
        )->willReturn($response->reveal());

        $this->assertEquals($response->reveal(), $controller->deleteAction('123-123-123'));
    }

    public function testGetLocale()
    {
        $commandFactory = $this->prophesize(CommandFactoryInterface::class);
        $commandBus = $this->prophesize(CommandBus::class);
        $viewHandler = $this->prophesize(ViewHandlerInterface::class);
        $tokenStorage = $this->prophesize(TokenStorageInterface::class);

        $controller = new ResourceController(
            $commandFactory->reveal(),
            $commandBus->reveal(),
            $viewHandler->reveal(),
            \stdClass::class,
            'resources',
            'sprungbrett.resources',
            'de',
            $tokenStorage->reveal()
        );

        $request = new Request(['locale' => 'de']);

        $this->assertEquals('de', $controller->getLocale($request));
    }

    public function testGetSecurityContext()
    {
        $commandFactory = $this->prophesize(CommandFactoryInterface::class);
        $commandBus = $this->prophesize(CommandBus::class);
        $viewHandler = $this->prophesize(ViewHandlerInterface::class);
        $tokenStorage = $this->prophesize(TokenStorageInterface::class);

        $controller = new ResourceController(
            $commandFactory->reveal(),
            $commandBus->reveal(),
            $viewHandler->reveal(),
            \stdClass::class,
            'resources',
            'sprungbrett.resources',
            'de',
            $tokenStorage->reveal()
        );

        $this->assertEquals('sprungbrett.resources', $controller->getSecurityContext());
    }
}
