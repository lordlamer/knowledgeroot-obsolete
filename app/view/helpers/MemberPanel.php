<?php

class Zend_View_Helper_MemberPanel extends Zend_View_Helper_Abstract {
	/**
	 * show member panel
	 *
	 * @param string $name
	 * @param Knowledgeroot_User|Knowledgeroot_Group $member
	 * @param array $config
	 * @return string
	 */
	public function memberPanel($name, $member, $config = null) {
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

	    $memberType = "";
	    $memberId = "";

	    if($member instanceof Knowledgeroot_User) {
		$memberType = "user";
		$memberId = $member->getId();
	    }

	    if($member instanceof Knowledgeroot_Group) {
		$memberType = "group";
		$memberId = $member->getId();
	    }

	    if(isset($config['show_members']) && $config['show_members'] && $memberType == 'group') {
		$members = new Knowledgeroot_Db_GroupMember();
		$select = $members->select();
		$select->where('group_id = ?', $memberId);
	    } else {
		$members = new Knowledgeroot_Db_GroupMember();
		$select = $members->select();
		$select->where('member_id = ?', $memberId);
		$select->where('member_type = ?', $memberType);
	    }

	    $all = $members->fetchAll($select);
	    $members = array();
	    foreach($all as $value) {
		if(isset($config['show_members']) && $config['show_members'] && $memberType == 'group') {
		    if($value['member_type'] == 'user') {
			$user = new Knowledgeroot_User($value['member_id']);
			$members['U_'.$user->getId()] = array(
			  'name' => $user->getLogin(),
			);
		    } else {
			$group = new Knowledgeroot_Group($value['member_id']);
			$members['G_'.$group->getId()] = array(
			  'name' => $group->getName(),
			);
		    }
		} else {
		    $group = new Knowledgeroot_Group($value['group_id']);
		    $members['G_'.$group->getId()] = array(
		      'name' => $group->getName(),
		    );
		}
	    }

	    $view->permissions = $members;

	    $view->setScriptPath(APPLICATION_PATH . '/view/scripts/');
	    return $view->render('helpers/memberpanel.phtml');
	}
}
