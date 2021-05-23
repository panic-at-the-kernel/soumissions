<?php

use Slim\App;

return function (App $app) {
    // Define your routes here
    $app->map(["GET", "POST"], '/', \App\Controllers\DefaultController::class . ':home');
};
