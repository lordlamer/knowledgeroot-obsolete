<?php

class Zend_View_Helper_PermissionPanel extends Zend_View_Helper_Abstract {
	/**
	 * show permission panel
	 *
	 * @param string $name
	 * @param array $actions
	 * @param array $config
	 * @return string
	 *
	 * config options:
	 * bool show_save_button - show save button so that stuff will be saved per ajax - default: false
	 * bool add_acl_on_form_submit - should acl be submitted on form submit as value - default: false
	 * bool add_user_permissions - add full permissions for the user itself if permissions are empty - default: false
	 */
	public function permissionPanel($name, $actions, $config = null) {
	    $view = new Zend_View();

	    $view->name = $name;
	    $view->actions = $actions;

	    // get actual userid
	    $session = new Zend_Session_Namespace('user');
	    $view->userId = $session->id;

	    if(isset($config['show_save_button']) && $config['show_save_button']) {
		$view->showSaveButton = true;
	    } else {
		$view->showSaveButton = false;
	    }

	    if(isset($config['add_acl_on_form_submit']) && $config['add_acl_on_form_submit']) {
		$view->addAclOnFormSubmit = true;
	    } else {
		$view->addAclOnFormSubmit = false;
	    }

	    if(isset($config['add_user_permissions']) && $config['add_user_permissions']) {
		$view->addUserPermissions = true;
	    } else {
		$view->addUserPermissions = false;
	    }

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
	    return $view->render('helpers/permissionpanel.phtml');
	}
}
