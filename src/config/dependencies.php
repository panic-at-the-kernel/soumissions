<?php

use DI\ContainerBuilder;
use Psr\Container\ContainerInterface;

return function (ContainerBuilder $containerBuilder) {
    $containerBuilder->addDefinitions([
        "i18n" => function (ContainerInterface $container) {
            $i18n = new i18n\i18n("fr_FR", "fr_FR", ["fr_FR"], [
                "error" => false,
                "sections" => true,
            ]);
            return $i18n;
        },

        "twig" => function (ContainerInterface $ci) {
            $loader = new \Twig\Loader\FilesystemLoader(__DIR__ . "/../views");
            $config = [];

            if ($ci->get("settings")['cache']) {
                $config['cache'] = __DIR__ . "/../../cache/twig";
            }

            $twig = new \Twig\Environment($loader, $config);

            $i18nTwig = new \i18n\i18nTwigExtension($ci->get("i18n"));
            $twig->addExtension($i18nTwig);

            return $twig;

        },
    ]);
};
