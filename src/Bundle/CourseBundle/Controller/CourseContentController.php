<?php

declare(strict_types=1);

namespace Sprungbrett\Bundle\CourseBundle\Controller;

use FOS\RestBundle\Controller\Annotations as Rest;
use Sprungbrett\Bundle\ContentBundle\Controller\ContentController;
use Sprungbrett\Bundle\CourseBundle\Admin\CourseAdmin;
use Sprungbrett\Bundle\CourseBundle\Model\Course\Message\PublishCourseMessage;
use Sulu\Component\Security\SecuredControllerInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Rest\NamePrefix("sprungbrett.")
 * @Rest\RouteResource("course-content")
 */
class CourseContentController extends ContentController implements SecuredControllerInterface
{
    protected function handlePublish(string $resourceId, string $locale): void
    {
        $this->messageBus->dispatch(new PublishCourseMessage($resourceId, $locale));
    }

    protected function getResourceKey(): string
    {
        return 'courses';
    }

    public function getSecurityContext()
    {
        return CourseAdmin::SECURITY_CONTEXT;
    }

    public function getLocale(Request $request)
    {
        return $request->get('locale');
    }
}
