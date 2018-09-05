<?php

namespace Sprungbrett\Bundle\InfrastructureBundle\DependencyInjection;

use League\Tactician\Doctrine\ORM\TransactionMiddleware;
use League\Tactician\Logger\LoggerMiddleware;
use Sprungbrett\Component\EventMiddleware\EventMiddleware;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;

class SprungbrettInfrastructureExtension extends Extension implements PrependExtensionInterface
{
    public function load(array $configs, ContainerBuilder $container)
    {
        $config = $this->processConfiguration(new Configuration(), $configs);
        $container->setParameter('sprungbrett_infrastructure.resources', $config['resources']);

        $loader = new XmlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $loader->load('services.xml');
    }

    public function prepend(ContainerBuilder $container)
    {
        if (!$container->hasExtension('tactician')) {
            throw new \RuntimeException('Missing TacticianBundle.');
        }

        $container->prependExtensionConfig(
            'tactician',
            [
                'commandbus' => [
                    'default' => [
                        'middleware' => [
                            LoggerMiddleware::class,
                            EventMiddleware::class,
                            TransactionMiddleware::class,
                            'tactician.middleware.command_handler',
                        ],
                    ],
                ],
            ]
        );

        if (!$container->hasExtension('doctrine')) {
            throw new \RuntimeException('Missing DoctrineBundle.');
        }

        $container->prependExtensionConfig(
            'doctrine',
            [
                'orm' => [
                    'mappings' => [
                        'Content' => [
                            'type' => 'xml',
                            'alias' => 'Content',
                            'prefix' => 'Sprungbrett\\Component\\Content\\Model',
                            'dir' => __DIR__ . '/../Resources/content/doctrine',
                            'is_bundle' => false,
                        ],
                        'Translation' => [
                            'type' => 'xml',
                            'alias' => 'Translation',
                            'prefix' => 'Sprungbrett\\Component\\Translation\\Model',
                            'dir' => __DIR__ . '/../Resources/translation/doctrine',
                            'is_bundle' => false,
                        ],
                    ],
                ],
            ]
        );
    }
}
