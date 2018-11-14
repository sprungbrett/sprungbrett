<?php

namespace Sprungbrett\Bundle\CoreBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use Symfony\Component\Yaml\Yaml;

class SprungbrettCoreExtension extends Extension implements PrependExtensionInterface
{
    public function load(array $configs, ContainerBuilder $container)
    {
        $locator = new FileLocator(__DIR__ . '/../Resources/config');
        $loader = new XmlFileLoader($container, $locator);

        $loader->load('services.xml');
    }

    public function prepend(ContainerBuilder $container)
    {
        if (!$container->hasExtension('framework')) {
            throw new \RuntimeException('Missing SymfonyFrameworkBundle');
        }

        $config = Yaml::parseFile(__DIR__ . '/../Resources/config/packages/messenger.yaml');
        $container->prependExtensionConfig('framework', $config['framework']);
    }
}
