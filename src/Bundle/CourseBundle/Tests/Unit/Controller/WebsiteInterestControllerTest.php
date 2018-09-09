<?php

namespace Sprungbrett\Bundle\CourseBundle\Tests\Unit\Controller;

use League\Tactician\CommandBus;
use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Sprungbrett\Bundle\CourseBundle\Controller\WebsiteInterestController;
use Sprungbrett\Bundle\CourseBundle\Model\Attendee\Command\ShowInterestCommand;
use Sprungbrett\Bundle\CourseBundle\Model\Attendee\CourseAttendeeInterface;
use Sprungbrett\Bundle\CourseBundle\Model\Attendee\Query\CountAttendeeQuery;
use Sprungbrett\Bundle\CourseBundle\Model\Attendee\Query\IsAttendeeQuery;
use Sprungbrett\Bundle\CourseBundle\Model\Course\CourseInterface;
use Sulu\Bundle\ContactBundle\Entity\ContactInterface;
use Sulu\Bundle\SecurityBundle\Entity\User;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Templating\EngineInterface;

class WebsiteInterestControllerTest extends TestCase
{
    public function testCreateAction()
    {
        $request = $this->prophesize(Request::class);
        $request->getLocale()->willReturn('de');

        $commandBus = $this->prophesize(CommandBus::class);
        $engine = $this->prophesize(EngineInterface::class);
        $tokenStorage = $this->prophesize(TokenStorageInterface::class);

        $controller = new WebsiteInterestController($commandBus->reveal(), $engine->reveal(), $tokenStorage->reveal());

        $token = $this->prophesize(TokenInterface::class);
        $tokenStorage->getToken()->willReturn($token);

        $user = $this->prophesize(User::class);
        $contact = $this->prophesize(ContactInterface::class);
        $contact->getId()->willReturn(42);
        $user->getContact()->willReturn($contact->reveal());
        $token->getUser()->willReturn($user->reveal());

        $course = $this->prophesize(CourseInterface::class);
        $course->getRoutePath()->willReturn('/test');
        $courseAttendee = $this->prophesize(CourseAttendeeInterface::class);
        $courseAttendee->getCourse()->willReturn($course->reveal());

        $commandBus->handle(
            Argument::that(
                function (ShowInterestCommand $command) {
                    return 'course-123-123-123' === $command->getCourseId()
                        && 42 === $command->getAttendeeId()
                        && 'de' === $command->getLocalization()->getLocale();
                }
            )
        )->shouldBeCalled()->willReturn($courseAttendee->reveal());

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

        $controller = new WebsiteInterestController($commandBus->reveal(), $engine->reveal(), $tokenStorage->reveal());

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
                    if (!$command instanceof IsAttendeeQuery) {
                        return false;
                    }

                    return 'course-123-123-123' === $command->getCourseId() && 42 === $command->getAttendeeId();
                }
            )
        )->shouldBeCalled()->willReturn(true);

        $commandBus->handle(
            Argument::that(
                function ($command) {
                    if (!$command instanceof CountAttendeeQuery) {
                        return false;
                    }

                    return 'course-123-123-123' === $command->getCourseId();
                }
            )
        )->shouldBeCalled()->willReturn(142);

        $engine->render(
            '@SprungbrettCourse/Interest/index.html.twig',
            ['count' => 142, 'hasInterest' => true, 'id' => 'course-123-123-123']
        )->willReturn('<div/>');

        $response = $controller->renderAction('course-123-123-123');
        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals('<div/>', $response->getContent());
    }
}
