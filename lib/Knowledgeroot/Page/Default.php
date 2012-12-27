<?php

/**
 *
 */
class Knowledgeroot_Page_Default extends Zend_Controller_Plugin_Abstract {
    public function preDispatch(Zend_Controller_Request_Abstract $request) {
	$config = Knowledgeroot_Registry::get('config');

	if($config->misc->defaultpage != '') {
	    $module = $request->getModuleName();
	    $controller = $request->getControllerName();
	    $action = $request->getActionName();

	    if($module == 'default' && $controller == 'index' && $action == 'index') {
		$this->_response->setRedirect($config->misc->defaultpage);
	    }
	}
    }
}