<?php

class Zend_View_Helper_PermissionPanel extends Zend_View_Helper_Abstract {
	public function permissionPanel($name, $actions) {
	    $view = new Zend_View();

	    $view->name = $name;
	    $view->actions = $actions;

	    // available roles
	    $view->roles = array(
		'U_1' => 'test1',
		'U_2' => 'test2',
		'U_3' => 'test3',
		'U_4' => 'test4',
		'G_1' => 'group1',
		'G_2' => 'group2',
		'G_3' => 'group3',
		'G_4' => 'group4',
	    );

	    // active roles with permissions
	    $view->permissions = array(
		'U_1' => array(
		    'name' => 'test1',
		    'permissions' => array(
			    'new' => 'allow',
			    'edit' => 'allow',
			    'delete' => 'deny',
			    'show' => 'allow',
		    ),
		),

		'U_2' => array(
		    'name' => 'test2',
		    'permissions' => array(
			    'new' => 'allow',
			    'edit' => 'allow',
			    'delete' => 'allow',
			    'show' => 'allow',
		    ),
		),

		'U_3' => array(
		    'name' => 'test3',
		    'permissions' => array(
			    'new' => 'deny',
			    'edit' => 'deny',
			    'delete' => 'deny',
			    'show' => 'allow',
		    ),
		),

		'G_1' => array(
		    'name' => 'group1',
		    'permissions' => array(
			    'new' => 'allow',
			    'edit' => 'allow',
			    'delete' => 'allow',
			    'show' => 'allow',
		    ),
		),

		'G_2' => array(
		    'name' => 'group2',
		    'permissions' => array(
			    'new' => 'deny',
			    'edit' => 'deny',
			    'delete' => 'deny',
			    'show' => 'allow',
		    ),
		),

		'G_3' => array(
		    'name' => 'group3',
		    'permissions' => array(
			    'new' => 'deny',
			    'edit' => 'deny',
			    'delete' => 'deny',
			    'show' => 'allow',
		    ),
		),

		'G_4' => array(
		    'name' => 'group4',
		    'permissions' => array(
			    'new' => 'deny',
			    'edit' => 'deny',
			    'delete' => 'deny',
			    'show' => 'allow',
		    ),
		),
	    );

	    $view->setScriptPath(APPLICATION_PATH . '/view/scripts/');
	    return $view->render('permissionpanel.phtml');
	}
}
