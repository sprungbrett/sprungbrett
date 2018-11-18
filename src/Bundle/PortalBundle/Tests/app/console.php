<?php

declare(strict_types=1);

use Sulu\Component\HttpKernel\SuluKernel;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Input\ArgvInput;
use Symfony\Component\Debug\Debug;

set_time_limit(0);

require __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__ . '/AppKernel.php';

defined('SULU_CONTEXT') || define('SULU_CONTEXT', getenv('SULU_CONTEXT') ?: SuluKernel::CONTEXT_ADMIN);

if (!class_exists(Application::class)) {
    throw new \RuntimeException('You need to add "symfony/framework-bundle" as a Composer dependency.');
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

$kernel = new AppKernel($env, $debug);
$application = new Application($kernel);
$application->run($input);
