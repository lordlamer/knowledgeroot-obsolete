<?php

class Knowledgeroot_User {

    protected $id = null;
    protected $name = null;
    protected $passwordHash = null;
    protected $email = null;
    protected $theme = null;
    protected $language = null;
    protected $active = null;
    protected $deleted = null;
    protected $lastUpdatedBy = null;
    protected $lastUpdated = null;
    protected $createDate = null;

    public function __construct($id = null) {
	if ($id != null) {
	    $this->load((int) $id);
	}
    }

    public function load($id) {
	$content = new Knowledgeroot_Db_User();
	$row = $content->find($id);

	$this->id = $row[0]['id'];
	$this->name = $row[0]['name'];
	$this->passwordHash = $row[0]['password'];
	$this->email = $row[0]['email'];
	$this->theme = $row[0]['theme'];
	$this->language = $row[0]['language'];
	$this->active = $row[0]['enabled'];
	$this->deleted = $row[0]['deleted'];
	$this->lastUpdatedBy = $row[0]['lastupdatedby'];
	$this->lastUpdated = $row[0]['lastupdated'];
	$this->createDate = $row[0]['createdate'];
    }

    public function save() {
	$data = array();

	if ($this->name != null)
	    $data['name'] = $this->name;
	if ($this->passwordHash != null)
	    $data['password'] = $this->passwordHash;
	if ($this->email != null)
	    $data['email'] = $this->email;
	if ($this->theme != null)
	    $data['theme'] = $this->theme;
	if ($this->language != null)
	    $data['language'] = $this->language;
	if ($this->active != null)
	    $data['enabled'] = $this->active;

	// set lastUpdatedBy
	if ($this->lastUpdatedBy == null)
	    $this->lastUpdatedBy = 0; // set to guest user

	$data['lastupdatedby'] = $this->lastUpdatedBy;

	// set create date
	if ($this->createDate == null) {
	    $this->createDate = date("Y-m-d H:i:s", time());
	    $data['createdate'] = $this->createDate;
	}

	// set last updated
	$this->lastUpdated = date("Y-m-d H:i:s", time());
	$data['lastupdated'] = $this->lastUpdated;

	$content = new Knowledgeroot_Db_Content();

	if ($this->id == null) {
	    $this->id = $content->insert($data);
	} else {
	    $content->update($data, 'id = ' . $this->id);
	}
    }

    public function delete($id = null, $markOnly = true) {
	if ($id == null)
	    $id = $this->id;

	$content = new Knowledgeroot_Db_User();

	if ($markOnly == true) {
	    $data = array('deleted' => 1);
	    $content->update($data, 'id = ' . $id);
	} else {
	    $content->delete('id = ' . $id);
	}
    }

    public function setName($name) {
	$this->name = $name;
    }

    public function setPassword($password) {
	$this->passwordHash = Knowledgeroot_Password::hash($password);
    }

    public function setEmail($email) {
	$this->email = $email;
    }

    public function setTheme($theme) {
	$this->theme = $theme;
    }

    public function setLanguage($lang) {
	$this->language = $lang;
    }

    public function getName() {
	return $this->name;
    }

    public function getEmail() {
	return $this->email;
    }

    public function getTheme() {
	return $this->theme;
    }

    public function getLanguage() {
	return $this->language;
    }

    public function setActive($enabled) {
	$this->active = $enabled;
    }

    public function isActive() {
	return $this->active;
    }

    public function setLastUpdatedBy($userid) {
	$this->lastUpdatedBy = $userid;
    }

    public function getCreateDate() {
	return $this->createDate;
    }

    public function getLastUpdated() {
	return $this->lastUpdated;
    }

    public function getLastUpdatedBy() {
	return $this->lastUpdatedBy;
    }

}

?>