<?php

namespace KrApp;

class Module {

    public function getAutoloaderConfig() {
        return array(
            'Knowledgeroot' => __DIR__ . '/src/Knowledgeroot/'
        );
    }

    public function getRouterConfig() {
        return array(
            __DIR__ . '/route/'
        );
    }

    public function getHookConfig() {
        return array(
            __DIR__ . '/hook/'
        );
    }

    public function getViewConfig() {
        return array(
            __NAMESPACE__ => __DIR__ . '/view/'
        );
    }

    public function getNavigationConfig() {
        return array(
            'navigation' => array(
                'main' => array(
                    'dashboard' => array(
                        'label' => 'Dashboard',
                        'route' => 'dashboard',
                        'uri' => '/dashboard',
                        'icon' => 'fa fa-lg fa-fw fa-home',
                        'order' => -100,
                        'resource' => 'page_dashboard',
                    ),
                ),
            ),
        );
    }

    public function getConsoleConfig() {
        return array(
            'version' => array(
                'command' => '\App\Cli\Version',
            ),
        );
    }

}
