<?php

class PermissionsController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
    }

    public function indexAction() {
	$this->_forward('list');
    }

    public function listAction() {

    }

    public function saveAction() {
	$this->_helper->layout()->disableLayout();
	$this->_helper->viewRenderer->setNoRender(true);

	$acl = Knowledgeroot_Registry::get('acl');

	$params = $this->getAllParams();

	// clear old rights
	$acl->clearByResource($params['panelName']);

	// save new rights
	foreach($params['panelStore'] as $role => $value) {
	    foreach($value['permissions'] as $action => $right) {
		$acl->addAcl($role, $params['panelName'],$action,$right);
	    }
	}
    }
}