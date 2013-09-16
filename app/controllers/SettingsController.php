<?php

class SettingsController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
    }

    public function indexAction()
    {
	// get user session
	$session = new Zend_Session_Namespace('user');

	// check for non guest users
	if(!$session->valid)
		$this->_redirect('');

	// get user
	$user = new Knowledgeroot_User($session->id);

	// check for post
	if ($this->getRequest()->getMethod() == 'POST') {
	    $user->setFirstName($this->_getParam('first_name'));
	    $user->setLastName($this->_getParam('last_name'));
	    $user->setEmail($this->_getParam('email'));
	    $user->setLanguage($this->_getParam('language'));
	    $user->setTimezone($this->_getParam('timezone'));

	    // check for password change
	    if($this->_getParam('password') != '') {
		if($this->_getParam('password') == $this->_getParam('password1')) {
		    //  save password
		    $user->setPassword($this->_getParam('password'));

		    // display success message
		    Knowledgeroot_Message::success("Password changed","Your password was changed!");
		} else {
		    Knowledgeroot_Message::error("Password","Your password could not changed!");
		}
	    }

	    // save user
	    $user->save();

	    // save settings also to session
	    $session->language = $this->_getParam('language');
	    $session->timezone = $this->_getParam('timezone');

	    // display message
	    // TODO: translate text to new language here!
	    Knowledgeroot_Message::success("Settings","Your settings were saved");

	    // redirect to this page again
	    $this->_redirect('settings');
	}

	// prepare view vars
	$this->view->id = $user->getId();
	$this->view->login = $user->getLogin();
	$this->view->first_name = $user->getFirstName();
	$this->view->last_name = $user->getLastName();
	$this->view->email = $user->getEmail();
	$this->view->language = $user->getLanguage();
	$this->view->timezone = $user->getTimezone();

	// get translations
	$translation = Knowledgeroot_Registry::get('translate');
	$this->view->translations = $translation->getTranslations();

	// get timezones
	$this->view->timezones = Knowledgeroot_Timezone::getTimezones();
    }
}

