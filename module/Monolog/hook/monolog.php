<?php

$app->hook('slim.mvc.ready', function () use ($app) {
    // create logger
    $app->container->singleton('log', function() use ($app) {
        // get config
        $config = $app->config;

        // init logger
        return \Monolog\MonologFactory::create($config->log->file, $config->log->ident, $config->log->level);
    });
});
