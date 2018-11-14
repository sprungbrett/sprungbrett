<?php

declare(strict_types=1);

use Sprungbrett\Bundle\CoreBundle\SprungbrettCoreBundle;
use Sprungbrett\Bundle\CourseBundle\SprungbrettCourseBundle;
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

        $loader->load(__DIR__ . '/config/doctrine.yaml');
    }

    public function registerBundles()
    {
        $bundles = parent::registerBundles();
        $bundles[] = new SprungbrettCoreBundle();
        $bundles[] = new SprungbrettCourseBundle();

        return array_filter(
            $bundles,
            function (Bundle $bundle) {
                return !$bundle instanceof SuluAudienceTargetingBundle
                    && !$bundle instanceof SuluResourceBundle;
            }
        );
    }
}