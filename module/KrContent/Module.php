<?php

namespace KrContent;

class Module {

    public function getAutoloaderConfig() {
        return array(
            'Knowledgeroot\\Content' => __DIR__ . '/src/Knowledgeroot/Content/'
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

}
