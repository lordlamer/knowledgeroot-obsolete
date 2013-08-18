<?php

class UserController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
    }

    public function indexAction()
    {
        $this->_forward('list');
    }

    public function listAction() {
	$users = Knowledgeroot_User::getUsers();

	$this->view->users = $users;
    }

    public function newAction()
    {
	if ($this->getRequest()->getMethod() == 'POST') {
	    if ($this->_getParam('button') == 'close')
		$this->_redirect('user/');

	    $user = new Knowledgeroot_User();
	    $user->setLogin($this->_getParam('login'));

	    // check if password is changed
	    if($this->_getParam('password') != '' && $this->_getParam('password2') != '' && $this->_getParam('password') == $this->_getParam('password2')) {
		$user->setPassword($this->_getParam('password'));
	    }

	    $user->setFirstName($this->_getParam('firstname'));
	    $user->setLastName($this->_getParam('lastname'));
	    $user->setEmail($this->_getParam('email'));
	    $user->setLanguage($this->_getParam('language'));
	    $user->setTimezone($this->_getParam('timezone'));
	    $user->setActive($this->_getParam('active'));
	    $user->save();

	    if ($this->_getParam('button') == 'save') {
		$this->_redirect('user/edit/' . $user->getId());
	    } else {
		$this->_redirect('user/');
	    }
	} else {
	    $this->view->action = 'new';

	    $this->renderScript("user/user.phtml");
	}
    }

    public function editAction()
    {
	if ($this->getRequest()->getMethod() == 'POST') {
	    if ($this->_getParam('button') == 'close')
		$this->_redirect('user/');

	    $user = new Knowledgeroot_User($this->_getParam('id'));
	    $user->setLogin($this->_getParam('login'));

	    // check if password is changed
	    if($this->_getParam('password') != '' && $this->_getParam('password2') != '' && $this->_getParam('password') == $this->_getParam('password2')) {
		$user->setPassword($this->_getParam('password'));
	    }

	    $user->setFirstName($this->_getParam('firstname'));
	    $user->setLastName($this->_getParam('lastname'));
	    $user->setEmail($this->_getParam('email'));
	    $user->setLanguage($this->_getParam('language'));
	    $user->setTimezone($this->_getParam('timezone'));
	    $user->setActive($this->_getParam('active'));
	    $user->save();

	    // remove existing group memberships
	    Knowledgeroot_Group::deleteMemberFromGroups($user);

	    // save group membership
	    foreach(Knowledgeroot_Util::objectToArray(json_decode($this->_getParam('member'))) as $memberId => $value) {
		// we only can be a member of a group
		if($memberId[0] == 'G') {
		    $id = substr($memberId,2);

		    $group = new Knowledgeroot_Group($id);
		    $group->addMember($user);
		}
	    }

	    if ($this->_getParam('button') == 'save') {
		$this->_redirect('user/edit/' . $user->getId());
	    } else {
		$this->_redirect('user/');
	    }
	} else {
	    $id = $this->_getParam('id');
	    $user = new Knowledgeroot_User($id);

	    $this->view->action = 'edit';
	    $this->view->id = $user->getId();
	    $this->view->login = $user->getLogin();
	    $this->view->firstname = $user->getFirstName();
	    $this->view->lastname = $user->getLastName();
	    $this->view->email = $user->getEmail();
	    $this->view->timezone = $user->getTimezone();
	    $this->view->language = $user->getLanguage();
	    $this->view->active = $user->getActive();

	    $this->renderScript("user/user.phtml");
	}
    }

    public function deleteAction()
    {
	$id = $this->_getParam('id');
	$user = new Knowledgeroot_User($id);

	$user->delete();

	$this->_redirect('user/');
    }

    public function enableAction()
    {
	$id = $this->_getParam('id');
	$user = new Knowledgeroot_User($id);

	$user->setActive(true);
	$user->save();

	$this->_redirect('user/');
    }

    public function disableAction()
    {
	$id = $this->_getParam('id');
	$user = new Knowledgeroot_User($id);

	$user->setActive(false);
	$user->save();

	$this->_redirect('user/');
    }
}







