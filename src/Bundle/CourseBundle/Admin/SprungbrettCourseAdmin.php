<?php

namespace Sprungbrett\Bundle\CourseBundle\Admin;

use Sulu\Bundle\AdminBundle\Admin\Admin;
use Sulu\Bundle\AdminBundle\Admin\Routing\Route;
use Sulu\Bundle\AdminBundle\Navigation\Navigation;
use Sulu\Bundle\AdminBundle\Navigation\NavigationItem;
use Sulu\Component\Security\Authorization\PermissionTypes;
use Sulu\Component\Security\Authorization\SecurityCheckerInterface;

class SprungbrettCourseAdmin extends Admin
{
    const SECURITY_CONTEXT = 'sprungbrett.course.course';

    /**
     * @var SecurityCheckerInterface
     */
    private $securityChecker;

    public function __construct(
        SecurityCheckerInterface $securityChecker,
        string $title
    ) {
        $this->securityChecker = $securityChecker;

        $this->setNavigation(new Navigation(new NavigationItem($title)));
    }

    public function getNavigationV2(): Navigation
    {
        $rootNavigationItem = $this->getNavigationItemRoot();

        $module = new NavigationItem('spruntbrett.course');
        $module->setPosition(40);
        $module->setIcon('fa-graduation-cap');

        if ($this->securityChecker->hasPermission(self::SECURITY_CONTEXT, PermissionTypes::VIEW)) {
            $events = new NavigationItem('spruntbrett.course');
            $events->setPosition(10);
            $events->setMainRoute('sprungbrett.course.courses_datagrid');

            $module->addChild($events);
        }

        if ($module->hasChildren()) {
            $rootNavigationItem->addChild($module);
        }

        return new Navigation($rootNavigationItem);
    }

    public function getRoutes(): array
    {
        return $this->createBasicRoutes('courses', '/courses');
    }

    public function getSecurityContexts()
    {
        return [
            'Sulu' => [
                'Sprungbrett' => [
                    self::SECURITY_CONTEXT => [
                        PermissionTypes::VIEW,
                        PermissionTypes::ADD,
                        PermissionTypes::EDIT,
                        PermissionTypes::DELETE,
                    ],
                ],
            ],
        ];
    }

    /**
     * @return Route[]
     */
    private function createBasicRoutes(string $resourceKey, string $route): array
    {
        return [
            (new Route(sprintf('sprungbrett.course.%s_datagrid', $resourceKey), $route, 'sulu_admin.datagrid'))
                ->addOption('title', sprintf('sprungbrett.%s', $resourceKey))
                ->addOption('adapters', ['table'])
                ->addOption('resourceKey', $resourceKey)
                ->addOption('addRoute', sprintf('sprungbrett.course.%s_add_form.detail', $resourceKey))
                ->addOption('editRoute', sprintf('sprungbrett.course.%s_edit_form.detail', $resourceKey)),
            (new Route(sprintf('sprungbrett.course.%s_add_form', $resourceKey), $route . '/add', 'sulu_admin.resource_tabs'))
                ->addOption('resourceKey', $resourceKey),
            (new Route(sprintf('sprungbrett.course.%s_add_form.detail', $resourceKey), '/details', 'sulu_admin.form'))
                ->addOption('tabTitle', 'sprungbrett.details')
                ->addOption('backRoute', sprintf('sprungbrett.course.%s_datagrid', $resourceKey))
                ->addOption('editRoute', sprintf('sprungbrett.course.%s_edit_form.detail', $resourceKey))
                ->setParent(sprintf('sprungbrett.course.%s_add_form', $resourceKey)),
            (new Route(sprintf('sprungbrett.course.%s_edit_form', $resourceKey), $route . '/:id', 'sulu_admin.resource_tabs'))
                ->addOption('resourceKey', $resourceKey),
            (new Route(sprintf('sprungbrett.course.%s_edit_form.detail', $resourceKey), '/details', 'sulu_admin.form'))
                ->addOption('tabTitle', 'sprungbrett.details')
                ->addOption('backRoute', sprintf('sprungbrett.course.%s_datagrid', $resourceKey))
                ->setParent(sprintf('sprungbrett.course.%s_edit_form', $resourceKey)),
        ];
    }
}
