<?php

namespace Twig;

class Module {
	public function getAutoloaderConfig() {
		return array(
			__NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__ . '/'
		);
	}

	public function getHookConfig() {
		return array(
			__DIR__ . '/hook/'
		);
	}
}
