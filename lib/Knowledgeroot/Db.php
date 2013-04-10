<?php

class Knowledgeroot_Db {
	public static function true() {
		$db = Knowledgeroot_Registry::get('db');

		switch($db) {
			case ($db instanceof Zend_Db_Adapter_Pdo_Sqlite):
			case ($db instanceof Zend_Db_Adapter_Sqlsrv):
			case ($db instanceof Zend_Db_Adapter_Pdo_Mysql):
			case ($db instanceof Zend_Db_Adapter_Mysqli):
				return 1;

			default:
				return 'true';
		}
	}

	public static function false() {
		$db = Knowledgeroot_Registry::get('db');

		switch($db) {
			case ($db instanceof Zend_Db_Adapter_Pdo_Sqlite):
			case ($db instanceof Zend_Db_Adapter_Sqlsrv):
			case ($db instanceof Zend_Db_Adapter_Pdo_Mysql):
			case ($db instanceof Zend_Db_Adapter_Mysqli):
				return 0;

			default:
				return 'false';
		}
	}
}