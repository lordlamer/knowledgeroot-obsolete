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
		$modulConfig = new Zend_Config_Ini($module->getConfigPath());

		// check if autoloader should include module lib path
		if($modulConfig->module->lib->path) {
		    // add module lib to include path
		    set_include_path(implode(PATH_SEPARATOR, array(
			realpath($modulePath . '/' . $modulConfig->module->lib->path),
			get_include_path(),
		    )));

		    // add module prefix to autoloader
		    $autoloader = Knowledgeroot_Registry::get('loader');
		    $autoloader->registerNamespace($moduleFileClass.'_');
		}

		// check for bootstrap
		if($modulConfig->module->bootstrap->path && $modulConfig->module->bootstrap->class) {
		    // get bootstrap
		    $bootstrapPath = $modulePath . '/' . $modulConfig->module->bootstrap->path;
		    $boostrapClass = $modulConfig->module->bootstrap->class;

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
