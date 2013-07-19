<?php

class Zend_View_Helper_MemberPanel extends Zend_View_Helper_Abstract {
	/**
	 * show member panel
	 *
	 * @param string $name
	 * @param array $config
	 * @return string
	 */
	public function memberPanel($name, $config = null) {
	    $view = new Zend_View();

	    $view->name = $name;

	    // available roles
	    $roles = array();


	    if(!isset($config['show_users']) || (isset($config['show_users']) && $config['show_users'])) {
		    $users = Knowledgeroot_User::getUsers();
		    foreach($users as $value) {
			$roles['U_' . $value->getId()] = $value->getLogin() . ' (U)';
		    }
            }

	    if(!isset($config['show_groups']) || (isset($config['show_groups']) && $config['show_groups'])) {
		    $groups = Knowledgeroot_Group::getGroups();
		    foreach($groups as $value) {
			$roles['G_' . $value->getId()] = $value->getName() . ' (G)';
		    }
            }

	    $view->roles = $roles;

            $view->permissions = array();

	    $view->setScriptPath(APPLICATION_PATH . '/view/scripts/');
	    return $view->render('memberpanel.phtml');
	}
}
