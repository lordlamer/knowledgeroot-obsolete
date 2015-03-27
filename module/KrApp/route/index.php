<?php

$app->get('/', function () use ($app) {
    echo $app->twig->render("@KrApp/index.html", array(
        'pagetitle' => 'Dashboard',
    ));
});
