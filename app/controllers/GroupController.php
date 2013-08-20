<?php

class GroupController extends Zend_Controller_Action
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
	$groups = Knowledgeroot_Group::getGroups();

	$this->view->groups = $groups;
    }

    public function newAction()
    {
	if ($this->getRequest()->getMethod() == 'POST') {
	    if ($this->_getParam('button') == 'close')
		$this->_redirect('group/');

	    $group = new Knowledgeroot_Group();
	    $group->setName($this->_getParam('name'));
	    $group->setDescription($this->_getParam('description'));
	    $group->setActive($this->_getParam('active'));
	    $group->save();

	    if ($this->_getParam('button') == 'save') {
		$this->_redirect('group/edit/' . $group->getId());
	    } else {
		$this->_redirect('group/');
	    }
	} else {
	    $this->view->action = 'new';

	    $this->renderScript("group/group.phtml");
	}
    }

    public function editAction()
    {
	if ($this->getRequest()->getMethod() == 'POST') {
	    if ($this->_getParam('button') == 'close')
		$this->_redirect('group/');

	    $group = new Knowledgeroot_Group($this->_getParam('id'));
	    $group->setName($this->_getParam('name'));
	    $group->setDescription($this->_getParam('description'));
	    $group->setActive($this->_getParam('active'));
	    $group->save();

	    // remove existing group memberships
	    Knowledgeroot_Group::deleteMemberFromGroups($group);

	    // save group membership
	    foreach(Knowledgeroot_Util::objectToArray(json_decode($this->_getParam('memberpanel_group'))) as $memberId => $value) {
		// we only can be a member of a group
		if($memberId[0] == 'G') {
		    $id = substr($memberId,2);

		    $pgroup = new Knowledgeroot_Group($id);
		    $pgroup->addMember($group);
		}
	    }

	    Knowledgeroot_Group::deleteGroupMembers($group);
	    // save members
	    foreach(Knowledgeroot_Util::objectToArray(json_decode($this->_getParam('memberpanel_member'))) as $memberId => $value) {
		if($memberId[0] == 'G') {
		    $id = substr($memberId,2);

		    $mgroup = new Knowledgeroot_Group($id);
		    $group->addMember($mgroup);
		}

		if($memberId[0] == 'U') {
		    $id = substr($memberId,2);

		    $muser = new Knowledgeroot_User($id);
		    $group->addMember($muser);
		}
	    }

	    if ($this->_getParam('button') == 'save') {
		$this->_redirect('group/edit/' . $group->getId());
	    } else {
		$this->_redirect('group/');
	    }
	} else {
	    $id = $this->_getParam('id');
	    $group = new Knowledgeroot_Group($id);

	    $this->view->action = 'edit';
	    $this->view->id = $group->getId();
	    $this->view->name = $group->getName();
	    $this->view->description = $group->getDescription();
	    $this->view->active = $group->getActive();

	    $this->renderScript("group/group.phtml");
	}
    }

    public function deleteAction()
    {
	$id = $this->_getParam('id');
	$group = new Knowledgeroot_Group($id);

	$group->delete();

	$this->_redirect('group/');
    }

    public function enableAction()
    {
	$id = $this->_getParam('id');
	$group = new Knowledgeroot_Group($id);

	$group->setActive(true);
	$group->save();

	$this->_redirect('group/');
    }

    public function disableAction()
    {
	$id = $this->_getParam('id');
	$group = new Knowledgeroot_Group($id);

	$group->setActive(false);
	$group->save();

	$this->_redirect('group/');
    }
}







