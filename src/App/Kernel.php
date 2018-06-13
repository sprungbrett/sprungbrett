<?php

namespace Sprungbrett\App;

use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\Config\Resource\FileResource;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Kernel as BaseKernel;
use Symfony\Component\Routing\RouteCollectionBuilder;

class Kernel extends BaseKernel
{
    use MicroKernelTrait;

    const CONFIG_EXTS = '.{php,xml,yaml,yml}';

    /**
     * @var string
     */
    protected $context;

    /**
     * @var array
     */
    protected $configuration;

    public function __construct(string $environment, $debug)
    {
        parent::__construct($environment, $debug);

        $this->context = $this->detectContext();

        $configurations = require $this->getProjectDir() . '/config/contexts.php';
        $this->configuration = $configurations[$this->context];
    }

    protected function detectContext(): string
    {
        if (!$_SERVER || !array_key_exists('REQUEST_URI', $_SERVER)) {
            return getenv('APP_CONTEXT') ?: 'admin';
        }

        return preg_match('/^\/admin(\/|$)/', $_SERVER['REQUEST_URI']) ? 'admin' : 'website';
    }

    public function getCacheDir()
    {
        return $this->getProjectDir() . '/var/cache/' . $this->context . '/' . $this->environment;
    }

    public function getLogDir()
    {
        return $this->getProjectDir() . '/var/log/' . $this->context;
    }

    public function registerBundles()
    {
        $contents = require $this->getProjectDir() . '/config/bundles.php';
        foreach ($contents as $class => $envs) {
            if (in_array($class, $this->configuration['excluded_bundles'])) {
                continue;
            }

            if (isset($envs['all']) || isset($envs[$this->environment])) {
                yield new $class();
            }
        }
    }

    protected function configureContainer(ContainerBuilder $container, LoaderInterface $loader)
    {
        $container->addResource(new FileResource($this->getProjectDir() . '/config/bundles.php'));
        $container->addResource(new FileResource($this->getProjectDir() . '/config/contexts.php'));

        // Feel free to remove the "container.autowiring.strict_mode" parameter
        // if you are using symfony/dependency-injection 4.0+ as it's the default behavior
        $container->setParameter('container.autowiring.strict_mode', true);
        $container->setParameter('container.dumper.inline_class_loader', true);
        $confDir = $this->getProjectDir() . '/config';

        $loader->load($confDir . '/{packages}/*' . self::CONFIG_EXTS, 'glob');
        $loader->load($confDir . '/{packages}/' . $this->environment . '/**/*' . self::CONFIG_EXTS, 'glob');
        $loader->load($confDir . '/' . $this->context . '/*' . self::CONFIG_EXTS, 'glob');
        $loader->load($confDir . '/{services}' . self::CONFIG_EXTS, 'glob');
        $loader->load($confDir . '/{services}_' . $this->environment . self::CONFIG_EXTS, 'glob');
    }

    protected function configureRoutes(RouteCollectionBuilder $routes)
    {
        $confDir = $this->getProjectDir() . '/config';

        $routes->import($confDir . '/{routes}/*' . self::CONFIG_EXTS, '/', 'glob');
        $routes->import($confDir . '/{routes}/' . $this->environment . '/**/*' . self::CONFIG_EXTS, '/', 'glob');
        $routes->import($confDir . '/' . $this->context . '/{routes}/' . $this->environment . '/**/*' . self::CONFIG_EXTS, '/', 'glob');
        $routes->import($confDir . '/' . $this->context . '/{routes}/*' . self::CONFIG_EXTS, '/', 'glob');
        $routes->import($confDir . '/{routes}' . self::CONFIG_EXTS, '/', 'glob');
    }

    public function getProjectDir()
    {
        return dirname(dirname(__DIR__));
    }

    protected function getKernelParameters()
    {
        return array_merge(
            parent::getKernelParameters(),
            [
                'sulu.context' => $this->context,
                'sulu.cache_dir' => $this->getCacheDir() . '/sulu',
                'kernel.public_dir' => $this->getProjectDir() . '/public',
                'kernel.project_dir' => $this->getProjectDir(),
            ]
        );
    }

    public function getName()
    {
        return $this->context;
    }
}
