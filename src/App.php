<?php

namespace App;

use DI\Container;
use Exception;
use Slim\Factory\AppFactory;
use Slim\Factory\ServerRequestCreatorFactory;
use Slim\ResponseEmitter;

class App
{

    private $container;
    private $app;

    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    public function init()
    {
        AppFactory::setContainer($this->container);
        $this->app = AppFactory::create();

        // Middlewares
        if (file_exists(__DIR__ . "/middlewares.php")) {
            $middlewares = require __DIR__ . "/middlewares.php";
            $middlewares($this->app);
        }

        // Routes
        if (!file_exists(__DIR__ . "/routes.php")) {
            throw new Exception("Routes file not found", 1);
        }
        $routes = require __DIR__ . "/routes.php";
        $routes($this->app);
    }

    public function run()
    {
        $serverRequestCreator = ServerRequestCreatorFactory::create();
        $request = $serverRequestCreator->createServerRequestFromGlobals();

        $this->app->addRoutingMiddleware();

        $this->app->addErrorMiddleware($this->container->get('settings')['displayErrorDetails'], false, false);

        $response = $this->app->handle($request);
        $responseEmitter = new ResponseEmitter();
        $responseEmitter->emit($response);
    }
}
