<?php

class Knowledgeroot_User {

    protected $id = null;
    protected $first_name = null;
    protected $last_name = null;
    protected $login = null;
    protected $email = null;
    protected $passwordHash = null;
    protected $language = null;
    protected $timezone = null;
    protected $time_start = null;
    protected $time_end = null;
    protected $created_by = null;
    protected $create_date = null;
    protected $changed_by = null;
    protected $change_date = null;
    protected $active = null;
    protected $deleted = null;

    public function __construct($id = null) {
	if ($id != null) {
	    $this->load((int) $id);
	}
    }

    public function load($id) {
	$content = new Knowledgeroot_Db_User();
	$row = $content->find($id);

	$this->id = $row[0]['id'];
	$this->first_name = $row[0]['first_name'];
	$this->last_name = $row[0]['last_name'];
	$this->login = $row[0]['login'];
	$this->email = $row[0]['email'];
	$this->passwordHash = $row[0]['password'];
	$this->language = $row[0]['language'];
	$this->timezone = $row[0]['timezone'];
	$this->time_start = $row[0]['time_start'];
	$this->time_end = $row[0]['time_end'];
	$this->active = $row[0]['active'];
	$this->created_by = $row[0]['created_by'];
	$this->create_date = $row[0]['create_date'];
	$this->changed_by = $row[0]['changed_by'];
	$this->change_date = $row[0]['change_date'];
	$this->deleted = $row[0]['deleted'];
    }

    public function save() {
	$data = array();

	if ($this->first_name != null)
	    $data['first_name'] = $this->first_name;
	if ($this->last_name != null)
	    $data['last_name'] = $this->last_name;
	if ($this->login != null)
	    $data['login'] = $this->login;
	if ($this->email != null)
	    $data['email'] = $this->email;
	if ($this->passwordHash != null)
	    $data['password'] = $this->passwordHash;
	if ($this->language != null)
	    $data['language'] = $this->language;
	if ($this->timezone != null)
	    $data['timezone'] = $this->timezone;
	if ($this->time_start != null)
	    $data['time_start'] = $this->time_start;
	if ($this->time_end != null)
	    $data['time_end'] = $this->time_end;
	if ($this->active != null)
	    $data['active'] = $this->active;

	// set changed_by
	if ($this->created_by === null)
	    $this->created_by = (($session->id !== null) ? $session->id : 0); // set to guest user

	// set changed_by
	if ($this->changed_by === null)
	    $this->changed_by = (($session->id !== null) ? $session->id : 0); // set to guest user

	if ($this->created_by !== null)
	    $data['created_by'] = $this->created_by;
	if ($this->changed_by !== null)
	    $data['changed_by'] = $this->changed_by;

	// create date object
	$date = new Knowledgeroot_Date();

	// set create date
	if ($this->create_date == null) {
	    $this->create_date = $date->getDbDate();
	    $data['create_date'] = $this->create_date;
	}

	// set last updated
	$this->change_date = $date->getDbDate();
	$data['change_date'] = $this->change_date;

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

    public function setFirstName($name) {
	$this->first_name = $name;
    }

    public function setLastName($name) {
	$this->last_name = $name;
    }

    public function setLogin($login) {
	$this->login = $login;
    }

    public function setEmail($email) {
	$this->email = $email;
    }

    public function setPassword($password) {
	$this->passwordHash = Knowledgeroot_Password::hash($password);
    }

    public function setLanguage($lang) {
	$this->language = $lang;
    }

    public function setTimezone($timezone) {
	$this->timezone = $timezone;
    }

    public function setActive($active) {
	$this->active = $active;
    }

    public function setTimeStart($time) {
	$this->time_start = $time;
    }

    public function setTimeEnd($time) {
	$this->time_end = $time;
    }

    public function setChangedBy($userid) {
	$this->changed_by = $userid;
    }

    public function getFirstName() {
	return $this->first_name;
    }

    public function getLastName() {
	return $this->last_name;
    }

    public function getLogin() {
	return $this->login;
    }

    public function getEmail() {
	return $this->email;
    }

    public function getLanguage() {
	return $this->language;
    }

    public function getTimezone() {
	return $this->timezone;
    }

    public function isActive() {
	return $this->active;
    }

    public function getActive() {
	return $this->active;
    }

    public function getTimeStart() {
	return $this->time_start;
    }

    public function getTimeEnd() {
	return $this->time_end;
    }

    public function getCreateDate() {
	return $this->create_date;
    }

    public function getChangeDate() {
	return $this->change_date;
    }

    public function getChangedBy() {
	return $this->changed_by;
    }
}

?>