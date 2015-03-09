<?php

$app->hook('slim.mvc.ready', function () use ($app) {

    $app->container->singleton('twig', function() use ($app) {
        return \Twig\TwigFactory::create($app->viewModules, PROJECT_PATH . '/data/cache/twig', $app->config->base->href);
    });
});
