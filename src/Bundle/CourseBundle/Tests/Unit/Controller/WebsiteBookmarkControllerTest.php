<?php

namespace Sprungbrett\Bundle\CourseBundle\Tests\Unit\Controller;

use League\Tactician\CommandBus;
use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Sprungbrett\Bundle\CourseBundle\Controller\WebsiteBookmarkController;
use Sprungbrett\Bundle\CourseBundle\Model\Attendee\Command\BookmarkCourseCommand;
use Sprungbrett\Bundle\CourseBundle\Model\Attendee\Query\CountBookmarksQuery;
use Sprungbrett\Bundle\CourseBundle\Model\Attendee\Query\HasBookmarkQuery;
use Sprungbrett\Bundle\CourseBundle\Model\Course\CourseInterface;
use Sulu\Bundle\ContactBundle\Entity\ContactInterface;
use Sulu\Bundle\SecurityBundle\Entity\User;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Templating\EngineInterface;

class WebsiteBookmarkControllerTest extends TestCase
{
    public function testCreateAction()
    {
        $request = $this->prophesize(Request::class);
        $request->getLocale()->willReturn('de');

        $commandBus = $this->prophesize(CommandBus::class);
        $engine = $this->prophesize(EngineInterface::class);
        $tokenStorage = $this->prophesize(TokenStorageInterface::class);

        $controller = new WebsiteBookmarkController($commandBus->reveal(), $engine->reveal(), $tokenStorage->reveal());

        $token = $this->prophesize(TokenInterface::class);
        $tokenStorage->getToken()->willReturn($token);

        $user = $this->prophesize(User::class);
        $contact = $this->prophesize(ContactInterface::class);
        $contact->getId()->willReturn(42);
        $user->getContact()->willReturn($contact->reveal());
        $token->getUser()->willReturn($user->reveal());

        $course = $this->prophesize(CourseInterface::class);
        $course->getRoutePath()->willReturn('/test');

        $commandBus->handle(
            Argument::that(
                function (BookmarkCourseCommand $command) {
                    return 'course-123-123-123' === $command->getCourseId()
                        && 42 === $command->getAttendeeId()
                        && 'de' === $command->getLocalization()->getLocale();
                }
            )
        )->shouldBeCalled()->willReturn($course->reveal());

        /** @var RedirectResponse $response */
        $response = $controller->createAction($request->reveal(), 'course-123-123-123');
        $this->assertInstanceOf(RedirectResponse::class, $response);
        $this->assertEquals('/test?success=1', $response->getTargetUrl());
    }

    public function testRenderAction()
    {
        $commandBus = $this->prophesize(CommandBus::class);
        $engine = $this->prophesize(EngineInterface::class);
        $tokenStorage = $this->prophesize(TokenStorageInterface::class);

        $controller = new WebsiteBookmarkController($commandBus->reveal(), $engine->reveal(), $tokenStorage->reveal());

        $token = $this->prophesize(TokenInterface::class);
        $tokenStorage->getToken()->willReturn($token);

        $user = $this->prophesize(User::class);
        $contact = $this->prophesize(ContactInterface::class);
        $contact->getId()->willReturn(42);
        $user->getContact()->willReturn($contact->reveal());
        $token->getUser()->willReturn($user->reveal());

        $commandBus->handle(
            Argument::that(
                function ($command) {
                    if (!$command instanceof HasBookmarkQuery) {
                        return false;
                    }

                    return 'course-123-123-123' === $command->getCourseId() && 42 === $command->getAttendeeId();
                }
            )
        )->shouldBeCalled()->willReturn(true);

        $commandBus->handle(
            Argument::that(
                function ($command) {
                    if (!$command instanceof CountBookmarksQuery) {
                        return false;
                    }

                    return 'course-123-123-123' === $command->getCourseId();
                }
            )
        )->shouldBeCalled()->willReturn(142);

        $engine->render(
            '@SprungbrettCourse/Bookmark/index.html.twig',
            ['count' => 142, 'hasBookmark' => true, 'id' => 'course-123-123-123']
        )->willReturn('<div/>');

        $response = $controller->renderAction('course-123-123-123');
        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals('<div/>', $response->getContent());
    }
}
