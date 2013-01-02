<?php

class Knowledgeroot_Auth {
	private $username = null;
	private $password = null;
	protected $isValid = false;

	public function __construct($username = null, $password = null) {
		$this->setIdendity($username);
		$this->setCredential($password);
	}

	public function isValid() {
	    // get db class
	    $db = Knowledgeroot_Registry::get('db');

	    // get user from db
	    $user = $db->fetchRow("SELECT id, password FROM " . $db->quoteIdentifier('user') . " WHERE login=? AND active=?", array($this->username, Knowledgeroot_Db::true()));

	    // check password hashes
	    if($user && Knowledgeroot_Password::verify($this->password, $user['password'])) {
		$this->isValid = true;
		return true;
	    }

	    return false;
	}

	public function getIdendity() {
		return $this->username;
	}

	public function setIdendity($username) {
		$this->username = $username;
	}

	public function setCredential($password) {
		$this->password = $password;
	}

	public function saveSession() {
	    // if auth is not valid return
	    if(!$this->isValid)
		return;

	    // get db class
	    $db = Knowledgeroot_Registry::get('db');

	    // get user from db
	    $user = $db->fetchRow("SELECT id, login, language FROM " . $db->quoteIdentifier('user') . " WHERE login=?", array($this->username));

	    // get new session namespace and save data
	    $session = new Zend_Session_Namespace('user');
	    $session->valid = true;
	    $session->id = $user['id'];
	    $session->login = $user['login'];
	    $session->language = $user['language'];
	}
}

?>
