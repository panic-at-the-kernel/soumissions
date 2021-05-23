<?php

use App\App;
use DI\ContainerBuilder;
use Dotenv\Dotenv;

require __DIR__ . '/../vendor/autoload.php';

if (file_exists(__DIR__ . '/../.env')) {
    $dotenv = Dotenv::createUnsafeImmutable(dirname(__DIR__));
    $dotenv->load();
}

$containerBuilder = new ContainerBuilder();

if (getenv('ENVIRONMENT') == "prod" || getenv('ENVIRONMENT') == "production") {
    $containerBuilder->enableCompilation(__DIR__ . '/../cache/container');
}

// Global settings
$settings = require __DIR__ . '/../src/config/settings.php';
$settings($containerBuilder);

// Dependencies
$dependencies = require __DIR__ . '/../src/config/dependencies.php';
$dependencies($containerBuilder);

$container = $containerBuilder->build();

/**
 * Creating the App.
 */
$app = new App($container);
$app->init();
$app->run();
