<?php

use DI\ContainerBuilder;

return function (ContainerBuilder $containerBuilder) {
    $containerBuilder->addDefinitions([
        "settings" => [
            'displayErrorDetails' => !(getenv('ENVIRONMENT') == "prod" || getenv('ENVIRONMENT') == "production"),
            'cache' => (getenv('ENVIRONMENT') == "prod" || getenv('ENVIRONMENT') == "production"),
            // Add required settings here
        ]
    ]);
};
