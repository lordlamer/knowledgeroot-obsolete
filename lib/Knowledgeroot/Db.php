<?php

class Knowledgeroot_Db {
	public static function true() {
		$db = Knowledgeroot_Registry::get('db');

		if($db instanceof Zend_Db_Adapter_Pdo_Sqlite) {
			return 1;
		} else {
			return 'true';
		}
	}

	public static function false() {
		$db = Knowledgeroot_Registry::get('db');

		if($db instanceof Zend_Db_Adapter_Pdo_Sqlite) {
			return 0;
		} else {
			return 'false';
		}
	}
}