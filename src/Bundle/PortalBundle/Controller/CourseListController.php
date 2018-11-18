<?php

declare(strict_types=1);

namespace Sprungbrett\Bundle\PortalBundle\Controller;

use Sprungbrett\Bundle\PortalBundle\Model\CourseView\CourseViewInterface;
use Sprungbrett\Bundle\PortalBundle\Model\CourseView\Query\ListCourseViewQuery;
use Sulu\Bundle\WebsiteBundle\Controller\WebsiteController;
use Sulu\Component\Content\Compat\StructureInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;

class CourseListController extends WebsiteController
{
    use CourseViewTrait;

    public function indexAction(
        Request $request,
        StructureInterface $structure,
        $preview = false,
        $partial = false
    ): Response {
        /** @var MessageBusInterface $messageBus */
        $messageBus = $this->get('message_bus');
        $courses = $messageBus->dispatch(new ListCourseViewQuery($structure->getLanguageCode(), $request->get('p', 1)));

        $attributes = [
            'courses' => array_map(
                function (CourseViewInterface $courseView) {
                    return $this->resolveCourse($courseView);
                },
                $courses
            ),
        ];

        return $this->renderStructure($structure, $attributes, $preview, $partial);
    }
}
