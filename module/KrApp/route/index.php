<?php

$app->get('/', function () use ($app) {
    echo $app->twig->render("@Knowledgeroot/index.html", array(
        'pagetitle' => 'Dashboard',
    ));
});
