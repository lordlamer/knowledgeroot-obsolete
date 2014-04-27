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

    public function registerAction() {
	$config = Knowledgeroot_Registry::get('config');

	// check if register is enabled
	if(!$config->register->enable)
	    $this->_redirect('./');

	if($this->getRequest()->isPost()) {
	    try {
		$user = new Knowledgeroot_User();
		$user->setLogin($this->_getParam('login'));

		// check if password is changed
		if($this->_getParam('password') != '' && $this->_getParam('password1') != '' && $this->_getParam('password') == $this->_getParam('password1')) {
		    $user->setPassword($this->_getParam('password'));
		}

		$user->setFirstName($this->_getParam('first_name'));
		$user->setLastName($this->_getParam('last_name'));
		$user->setEmail($this->_getParam('email'));
		$user->setLanguage($this->_getParam('language'));
		$user->setTimezone($this->_getParam('timezone'));
		$user->setActive(true);
		$user->save();

		// success message
		Knowledgeroot_Message::success("User registered", "User is successfully registered");

		// redirect to homepage
		$this->_redirect('./');
	    } catch(Exception $e) {
		Knowledgeroot_Message::error("Registration failed", "Could not register user!");
		$this->_redirect('./register');
	    }
	}

	// get translations
	$translation = Knowledgeroot_Registry::get('translate');
	$this->view->translations = $translation->getTranslations();

	// get timezones
	$this->view->timezones = Knowledgeroot_Timezone::getTimezones();

	// check for captcha
	if($config->register->captcha) {
	    $captcha = new Zend_Captcha_Image();
	    $captcha->setImgDir(PROJECT_PATH . '/public/data/captcha/');
	    $captcha->setImgUrl($this->view->baseUrl('/data/captcha/'));
	    $captcha->setFont('/usr/share/fonts/truetype/ttf-dejavu/DejaVuSans.ttf');
	    $captcha->setWidth(350);
	    $captcha->setHeight(150);
	    $captcha->setWordlen(5);
	    $captcha->setFontSize(70);
	    $captcha->setLineNoiseLevel(3);
	    $captcha->generate();
	    $this->view->captcha = $captcha;
	}
    }
}



