<?php

$app->hook('slim.before.router', function () use ($app) {
    // set version to twig
    $app->twig->addGlobal('version', \Knowledgeroot\Version::VERSION);

    // set config to twig
    $config = $app->config;
    $app->twig->addGlobal('config', $config);

    // base url
    $app->twig->addGlobal('baseUrl', $config->base->base_url);

    // session data
    $session = new \Zend\Session\Container('user');
    $app->twig->addGlobal('session', $session);

    // add test function iAmAllowed
    $function = new Twig_SimpleFunction('iAmAllowed', function ($resource, $action) {
        return true;
    });
    $app->twig->addFunction($function);
});
