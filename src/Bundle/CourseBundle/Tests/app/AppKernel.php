<?php

/*
 * This file is part of Sulu.
 *
 * (c) MASSIVE ART WebServices GmbH
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

use League\Tactician\Bundle\TacticianBundle;
use Sprungbrett\Bundle\CourseBundle\SprungbrettCourseBundle;
use Sprungbrett\Bundle\InfrastructureBundle\SprungbrettInfrastructureBundle;
use Sulu\Bundle\TestBundle\Kernel\SuluTestKernel;
use Symfony\Component\Config\Loader\LoaderInterface;

class AppKernel extends SuluTestKernel
{
    public function registerBundles()
    {
        $bundles = [
            new TacticianBundle(),
            new SprungbrettInfrastructureBundle(),
            new SprungbrettCourseBundle(),
        ];

        return array_merge($bundles, parent::registerBundles());
    }

    public function registerContainerConfiguration(LoaderInterface $loader)
    {
        parent::registerContainerConfiguration($loader);

        $loader->load(__DIR__ . '/config/config.yml');
    }
}
