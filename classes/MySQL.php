<?php
	require_once('Constants.php');	

	class MySQL {
		
		public static function getInstance() {		
			$mysql = new mysqli(Constants::DB_HOST, Constants::DB_USERNAME, Constants::DB_PASSWORD, Constants::DB_SCHEMA);			
			if(!isset($mysql)) die('no database connection: ' . mysqli_connect_error());
			$mysql->autocommit(TRUE);
			return $mysql;
		}
	}
?>
