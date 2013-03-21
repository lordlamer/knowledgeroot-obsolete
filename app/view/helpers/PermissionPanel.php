<?php

class Zend_View_Helper_PermissionPanel extends Zend_View_Helper_Abstract {
	public function permissionPanel($name, $actions) {
	    $view = new Zend_View();

	    $view->name = $name;
	    $view->actions = $actions;

	    // available roles
	    $roles = array();

	    $users = Knowledgeroot_User::getUsers();
	    foreach($users as $value) {
		$roles['U_' . $value->getId()] = $value->getLogin() . ' (U)';
	    }

	    $groups = Knowledgeroot_Group::getGroups();
	    foreach($groups as $value) {
		$roles['G_' . $value->getId()] = $value->getName() . ' (G)';
	    }

	    $view->roles = $roles;

	    $acl = Knowledgeroot_Registry::get('acl');

	    // active roles with permissions
	    $view->permissions = $acl->getAclForResource($name);

	    $view->setScriptPath(APPLICATION_PATH . '/view/scripts/');
	    return $view->render('permissionpanel.phtml');
	}
}
