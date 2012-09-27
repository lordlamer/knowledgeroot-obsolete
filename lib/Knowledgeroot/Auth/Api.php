<?php

class Knowledgeroot_Auth_Api {
	private $apikey = null;

	public function __construct($apikey = null) {
		$this->setApiKey($apikey);
	}

	public function setApiKey($apikey) {
		$this->apikey = $apikey;
	}

	public function getApiKey() {
		return $this->apikey;
	}

	public function isValid() {
		$config = Knowledgeroot_Registry::get('config');

		if($this->apikey != null && $config->rest->apikey == $this->apikey)
			return true;
		else
			return false;
	}
}

?>