<?php

namespace Sprungbrett\Bundle\CourseBundle\Admin;

use Sulu\Bundle\AdminBundle\Admin\Admin;
use Sulu\Bundle\AdminBundle\Admin\Routing\Route;
use Sulu\Bundle\AdminBundle\Navigation\Navigation;
use Sulu\Bundle\AdminBundle\Navigation\NavigationItem;
use Sulu\Component\Localization\Localization;
use Sulu\Component\Security\Authorization\PermissionTypes;
use Sulu\Component\Security\Authorization\SecurityCheckerInterface;
use Sulu\Component\Webspace\Manager\WebspaceManagerInterface;

class CourseAdmin extends Admin
{
    const SECURITY_CONTEXT = 'sprungbrett.course.course';

    /**
     * @var WebspaceManagerInterface
     */
    private $webspaceManager;

    /**
     * @var SecurityCheckerInterface
     */
    private $securityChecker;

    public function __construct(WebspaceManagerInterface $webspaceManager, SecurityCheckerInterface $securityChecker)
    {
        $this->webspaceManager = $webspaceManager;
        $this->securityChecker = $securityChecker;
    }

    public function getNavigation(): Navigation
    {
        $rootNavigationItem = $this->getNavigationItemRoot();

        $module = new NavigationItem('sprungbrett.courses');
        $module->setPosition(40);
        $module->setIcon('fa-graduation-cap');

        if ($this->securityChecker->hasPermission(self::SECURITY_CONTEXT, PermissionTypes::VIEW)) {
            $events = new NavigationItem('sprungbrett.courses');
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
        // FIXME remove as soon as https://github.com/sulu/sulu/issues/3922 is fixed
        $locales = array_values(
            array_map(
                function (Localization $localization) {
                    return $localization->getLocale();
                },
                $this->webspaceManager->getAllLocalizations()
            )
        );

        $resourceKey = 'courses';
        $route = '/courses';

        return [
            (new Route(sprintf('sprungbrett.course.%s_datagrid', $resourceKey), $route . '/:locale', 'sulu_admin.datagrid'))
                ->addOption('title', sprintf('sprungbrett.%s', $resourceKey))
                ->addOption('adapters', ['table'])
                ->addOption('resourceKey', $resourceKey)
                ->addOption('locales', $locales)
                ->addAttributeDefault('locale', $locales ? $locales[0] : '')
                ->addOption('addRoute', sprintf('sprungbrett.course.%s_add_form.detail', $resourceKey))
                ->addOption('editRoute', sprintf('sprungbrett.course.%s_edit_form.detail', $resourceKey)),
            (new Route(sprintf('sprungbrett.course.%s_add_form', $resourceKey), $route . '/:locale/add', 'sulu_admin.resource_tabs'))
                ->addOption('resourceKey', $resourceKey)
                ->addOption('locales', $locales),
            (new Route(sprintf('sprungbrett.course.%s_add_form.detail', $resourceKey), '/details', 'sulu_admin.form'))
                ->addOption('tabTitle', 'sprungbrett.courses.details')
                ->addOption('backRoute', sprintf('sprungbrett.course.%s_datagrid', $resourceKey))
                ->addOption('editRoute', sprintf('sprungbrett.course.%s_edit_form.detail', $resourceKey))
                ->addOption('locales', $locales)
                ->addOption('toolbarActions', ['sulu_admin.save'])
                ->setParent(sprintf('sprungbrett.course.%s_add_form', $resourceKey)),
            (new Route(sprintf('sprungbrett.course.%s_edit_form', $resourceKey), $route . '/:locale/:id', 'sulu_admin.resource_tabs'))
                ->addOption('resourceKey', $resourceKey)
                ->addOption('locales', $locales),
            (new Route(sprintf('sprungbrett.course.%s_edit_form.detail', $resourceKey), '/details', 'sulu_admin.form'))
                ->addOption('tabTitle', 'sprungbrett.courses.details')
                ->addOption('backRoute', sprintf('sprungbrett.course.%s_datagrid', $resourceKey))
                ->addOption('locales', $locales)
                ->addOption('toolbarActions', ['sulu_admin.save', 'sulu_admin.delete'])
                ->setParent(sprintf('sprungbrett.course.%s_edit_form', $resourceKey)),
        ];
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
}
