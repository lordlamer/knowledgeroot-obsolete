<?php

$app->hook('slim.mvc.ready', function () use ($app) {
    // pase config
    $app->container->singleton('config', function() {
        // init config
        return \Config\ConfigFactory::create(PROJECT_PATH . '/config/app.ini');
    });
});
