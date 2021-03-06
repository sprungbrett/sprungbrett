<?php

declare(strict_types=1);

use Sprungbrett\Kernel;
use Sulu\Component\HttpKernel\SuluKernel;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Input\ArgvInput;
use Symfony\Component\Debug\Debug;
use Symfony\Component\Dotenv\Dotenv;

set_time_limit(0);

require __DIR__ . '/../vendor/autoload.php';

defined('SULU_CONTEXT') || define('SULU_CONTEXT', getenv('SULU_CONTEXT') ?: SuluKernel::CONTEXT_ADMIN);

if (!class_exists(Application::class)) {
    throw new \RuntimeException('You need to add "symfony/framework-bundle" as a Composer dependency.');
}

if (!isset($_SERVER['APP_ENV'])) {
    if (!class_exists(Dotenv::class)) {
        throw new \RuntimeException('APP_ENV environment variable is not defined. You need to define environment variables for configuration or add "symfony/dotenv" as a Composer dependency to load variables from a .env file.');
    }
    (new Dotenv())->load(__DIR__ . '/../.env');
}

$input = new ArgvInput();
$env = $input->getParameterOption(['--env', '-e'], $_SERVER['APP_ENV'] ?? 'dev', true);
$debug = (bool) ($_SERVER['APP_DEBUG'] ?? ('prod' !== $env)) && !$input->hasParameterOption('--no-debug', true);

if ($debug) {
    umask(0000);

    if (class_exists(Debug::class)) {
        Debug::enable();
    }
}

$kernel = new Kernel($env, $debug, SULU_CONTEXT);
if ('test' === $env) {
    require_once __DIR__ . '/../tests/app/AppKernel.php';

    $kernel = new AppKernel($env, $debug);
}

$application = new Application($kernel);
$application->run($input);
