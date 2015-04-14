<?php

$app->get('/page/jsontree', function () use ($app) {
    // get config
    $config = $app->config;

    // get all pages
    $pages = \Knowledgeroot\Page::getPages();

    // prepare dojo json store
    $out = array(
        'identifier' => 'id',
        'label' => 'name',
        'items' => array()
    );

    if (count($pages) > 0) {
        foreach ($pages as $key => $page) {
            // check for broken parentId to itself
            if ($page->getId() == $page->getParent())
                continue;

            // check if page is accessable
            if (!Knowledgeroot_Page_Path::isAccessable($page->getId()))
                continue;

            $item = array(
                'id' => $page->getId(),
                'parent' => (($page->getParent() != 0) ? $page->getParent() : '#'),
                'url' => $config->base->base_url . 'page/' . $page->getId(),
                'text' => $page->getName(),
                'type' => (($page->getParent() == 0) ? 'root' : 'page'),
                'tooltip' => $page->getTooltip(),
                'alias' => (($config->alias->enable && $page->getAlias() != "") ? $config->base->base_url . $config->alias->prefix . "/" . $page->getAlias() : ""),
                //'symlink' => $value['symlink'],
                'sort' => $page->getSorting(),
                'icon' => $page->getIcon()
            );

            $out['items'][] = $item;
        }
    }

    echo json_encode($out);
});
