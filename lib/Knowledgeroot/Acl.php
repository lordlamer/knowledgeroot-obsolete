<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Acl
 *
 * @author fhabermann
 */
class Knowledgeroot_Acl extends Zend_Acl {

    protected $actions = array(
	'new',
	'edit',
	'delete',
	'show',
	'print',
	'export',
    );

    protected $acl = null;

    public function load() {
	$this->acl = new Zend_Acl();

	$this->loadRoles();

	$this->loadResources();

	$this->loadAcl();
    }

    protected function loadRoles() {
	$db = Knowledgeroot_Registry::get('db');

	// fetch all groups
	$rows = $db->fetchAll('SELECT id FROM ' . $db->quoteIdentifier('group') . ' WHERE active='.Knowledgeroot_Db::true().' AND deleted='.Knowledgeroot_Db::false());

	foreach($rows as $key => $value) {
	    $this->acl->addRole(new Zend_Acl_Role('G_' . $value['id']));
	}

	// fetch all group and create group members
	$rows = $db->fetchAll('SELECT id FROM ' . $db->quoteIdentifier('group') . ' WHERE active='.Knowledgeroot_Db::true().' AND deleted='.Knowledgeroot_Db::false());

	foreach($rows as $key => $value) {
	    $parents = array('G_'.$value['id']);
	    $gm = $db->fetchAll("SELECT group_id FROM group_member WHERE member_id=:gid AND member_type='group'", array('gid' => $value['id']));
	    foreach($gm as $mKey => $mValue) {
		$parents[] = 'G_' . $mValue['group_id'];
	    }

	    $this->acl->addRole(new Zend_Acl_Role('GM_' . $value['id']), $parents);
	}

	// fetch all users and set parent
	$rows = $db->fetchAll('SELECT id FROM ' . $db->quoteIdentifier('user') . ' WHERE active='.Knowledgeroot_Db::true().' AND deleted='.Knowledgeroot_Db::false());

	foreach($rows as $key => $value) {
	    $parents = array();
	    $gm = $db->fetchAll("SELECT group_id FROM group_member WHERE member_id=:uid AND member_type='user'", array('uid' => $value['id']));
	    foreach($gm as $mKey => $mValue) {
		$parents[] = 'GM_' . $mValue['group_id'];
	    }

	    $this->acl->addRole(new Zend_Acl_Role('U_' . $value['id']), $parents);
	}



    }

    protected function loadResources() {
	$db = Knowledgeroot_Registry::get('db');

	// load pages
	$pages = $db->fetchAll('SELECT id FROM page WHERE deleted='.Knowledgeroot_Db::false());

	foreach($pages as $key => $value) {
	    $this->acl->addResource(new Zend_Acl_Resource('P_' . $value['id']));
	}

	// load contents
	$content = $db->fetchAll('SELECT id FROM content WHERE deleted='.Knowledgeroot_Db::false());

	foreach($content as $key => $value) {
	    $this->acl->addResource(new Zend_Acl_Resource('C_' . $value['id']));
	}

	// load files
	$files = $db->fetchAll('SELECT id FROM ' . $db->quoteIdentifier('file') . ' WHERE deleted='.Knowledgeroot_Db::false());

	foreach($files as $key => $value) {
	    $this->acl->addResource(new Zend_Acl_Resource('F_' . $value['id']));
	}
    }

    protected function loadAcl() {
	$db = Knowledgeroot_Registry::get('db');

	$acl = $db->fetchAll('SELECT role_id, resource, action, ' . $db->quoteIdentifier('right') . ' FROM ' . $db->quoteIdentifier('acl'));

	foreach($acl as $key => $value) {
	    if($value['right'] == 'allow') {
		$this->acl->allow($value['role_id'], $value['resource'], $value['action']);
	    } else {
		$this->acl->deny($value['role_id'], $value['resource'], $value['right']);
	    }
	}
    }
}

?>