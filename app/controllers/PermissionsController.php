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

	print_r($this->getAllParams());

	echo "test";
    }
}