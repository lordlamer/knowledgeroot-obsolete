<?php

$app->hook('slim.mvc.ready', function () use ($app) {
    // create db
    $app->container->singleton('db', function() use ($app) {
        // get config
        $config = $app->config;

        return \Doctrine\DoctrineFactory::create($config->database->dsn);
    });
});
