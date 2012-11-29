<?php

/**
 *
 */
class Knowledgeroot_ModuleManager {
	public function loadModules() {
	    $modulesConfig = new Zend_Config_Ini(PROJECT_PATH . '/config/modules.ini');

	    foreach($modulesConfig->modules->toArray() as $key => $value) {
		if($value)
		    $this->loadModule($key);
	    }
	}

	/**
	 * load module by keyname
	 *
	 * @param type $keyname
	 */
	protected function loadModule($keyname) {
	    try {
		// define modulePath
		$modulePath = PROJECT_PATH . '/module/' . $keyname;

		// define moduleFile name
		$moduleFile = $modulePath . '/Module.php';

		// define moduleFile class
		$moduleFileClass = ucfirst($keyname).'Module';

		// include module file
		include_once($moduleFile);

		// init module file
		$module = new $moduleFileClass();

		// get modul config
		$moduleConfig = new Zend_Config_Ini($module->getConfigPath());

		// save config
		Knowledgeroot_Registry::set($keyname.'_config', $moduleConfig);

		// check if autoloader should include module lib path
		if($moduleConfig->module->lib->path) {
		    // add module lib to include path
		    set_include_path(implode(PATH_SEPARATOR, array(
			realpath($modulePath . '/' . $moduleConfig->module->lib->path),
			get_include_path(),
		    )));

		    // add module prefix to autoloader
		    $autoloader = Knowledgeroot_Registry::get('loader');
		    $autoloader->registerNamespace($moduleFileClass.'_');
		}

		// check for bootstrap
		if($moduleConfig->module->bootstrap->path && $moduleConfig->module->bootstrap->class) {
		    // get bootstrap
		    $bootstrapPath = $modulePath . '/' . $moduleConfig->module->bootstrap->path;
		    $boostrapClass = $moduleConfig->module->bootstrap->class;

		    // include bootstrap class
		    include_once($bootstrapPath);

		    // init bootstrap
		    $bootstrap = new $boostrapClass();
		}
	    } catch(Exception $e) {
		throw new Knowledgeroot_ModuleManager_Exception('Could not load Module:'.$keyname, 0, $e);
	    }
	}
}
