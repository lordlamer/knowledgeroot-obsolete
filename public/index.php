<?php

/**
 * Knowledgeroot
 *
 * Knowledgeroot Knowledgebase
 *
 * @author Frank Habermann <lordlamer@lordlamer.de>
 * @date 20150309
 */

// Define path to project directory
defined('PROJECT_PATH')
    || define('PROJECT_PATH', realpath(dirname(__FILE__) . '/..'));

// use composer autoload
require PROJECT_PATH . '/vendor/autoload.php';

// parse config
$config = parse_ini_file(PROJECT_PATH . '/config/app.ini', true);

// modules to load
$modules = array();

// get each active module
foreach($config['modules'] as $module => $enabled) {
	if($enabled)
		$modules[] = $module;
}

// init app
$app = new \SlimMVC\Application(PROJECT_PATH . '/module', $modules);

// run auth app
$app->run();
