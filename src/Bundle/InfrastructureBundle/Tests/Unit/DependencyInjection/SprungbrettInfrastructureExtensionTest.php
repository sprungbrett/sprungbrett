<?php

namespace Sprungbrett\Bundle\InfrastructureBundle\Tests\Unit\DependencyInjection;

use Doctrine\Bundle\DoctrineBundle\DependencyInjection\DoctrineExtension;
use League\Tactician\Bundle\DependencyInjection\TacticianExtension;
use League\Tactician\Doctrine\ORM\TransactionMiddleware;
use League\Tactician\Logger\LoggerMiddleware;
use Matthias\SymfonyDependencyInjectionTest\PhpUnit\AbstractExtensionTestCase;
use Sprungbrett\Bundle\InfrastructureBundle\DependencyInjection\SprungbrettInfrastructureExtension;

class SprungbrettInfrastructureExtensionTest extends AbstractExtensionTestCase
{
    protected function getContainerExtensions()
    {
        return [
            new DoctrineExtension(),
            new TacticianExtension(),
            new SprungbrettInfrastructureExtension(),
        ];
    }

    public function testPrependedConfig()
    {
        $this->container->setParameter('kernel.debug', false);
        $this->load();

        $config = $this->container->getExtensionConfig('tactician');
        $this->assertArrayHasKey('commandbus', $config[0]);

        $config = $this->container->getExtensionConfig('doctrine');
        $this->assertArrayHasKey('orm', $config[0]);
    }

    public function testRegisterServices()
    {
        $this->container->setParameter('kernel.debug', false);
        $this->load();

        $this->assertTrue($this->container->has(TransactionMiddleware::class));
        $this->assertTrue($this->container->has(LoggerMiddleware::class));
    }
}
