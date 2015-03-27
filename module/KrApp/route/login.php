<?php

$app->get('/login', function () use ($app) {
    echo $app->twig->render("@Knowledgeroot/login.html", array(
        'pagetitle' => 'Login',
    ));
});

$app->get('/logout', function () use ($app) {

});