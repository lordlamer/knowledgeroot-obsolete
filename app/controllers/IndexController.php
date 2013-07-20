<?php

class IndexController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
    }

    public function indexAction()
    {
        // action body
    }

    public function showAction()
    {
        // action body
    }

    public function loginAction() {
	$form = new Knowledgeroot_Form_Login();

	if($this->getRequest()->isPost()) {
	    if($form->isValid($_POST)) {
		// create auth object
		$auth = new Knowledgeroot_Auth($this->getRequest()->getParam('user'), $this->getRequest()->getParam('password'));

		// check auth
		if($auth->isValid()) {
		    $auth->saveSession();
		    Knowledgeroot_Message::success("Login", "Login successfull");
		    $this->_redirect('./');
		}
	    }

	    Knowledgeroot_Message::error("Could not Login","Could not Login");
	}
    }

    public function logoutAction() {
	Zend_Session::destroy(true);
	$this->_redirect('./');
    }

    public function languageAction() {
	// get translate object
	$translate = Knowledgeroot_Registry::get('translate');

	// set locale
	$translate->setLocale($this->getRequest()->getParam('language'), true);

	// redirect
	$this->_redirect('./');
    }
}



