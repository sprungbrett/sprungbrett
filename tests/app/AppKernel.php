<?php

declare(strict_types=1);

use Sprungbrett\Bundle\ContentBundle\SprungbrettContentBundle;
use Sprungbrett\Bundle\CoreBundle\SprungbrettCoreBundle;
use Sprungbrett\Bundle\CourseBundle\SprungbrettCourseBundle;
use Sprungbrett\Bundle\PortalBundle\SprungbrettPortalBundle;
use Sulu\Bundle\AudienceTargetingBundle\SuluAudienceTargetingBundle;
use Sulu\Bundle\ResourceBundle\SuluResourceBundle;
use Sulu\Bundle\TestBundle\Kernel\SuluTestKernel;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\HttpKernel\Bundle\Bundle;

/**
 * @deprecated
 */
class AppKernel extends SuluTestKernel
{
    public function registerContainerConfiguration(LoaderInterface $loader)
    {
        parent::registerContainerConfiguration($loader);

        $loader->load(__DIR__ . '/config/config.yaml');
    }

    public function registerBundles()
    {
        $bundles = parent::registerBundles();
        $bundles[] = new SprungbrettCoreBundle();
        $bundles[] = new SprungbrettContentBundle();
        $bundles[] = new SprungbrettCourseBundle();
        $bundles[] = new SprungbrettPortalBundle();

        return array_filter(
            $bundles,
            function (Bundle $bundle) {
                return !$bundle instanceof SuluAudienceTargetingBundle
                    && !$bundle instanceof SuluResourceBundle;
            }
        );
    }
}
