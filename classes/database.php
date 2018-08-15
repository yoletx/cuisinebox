<?php
class Database {
	private static $mysqli = null;

	static function connect(){
		self::$mysqli = new mysqli("192.168.1.14", "admin", "admin", "cuisinebox");
		if (self::$mysqli->connect_errno) {
		    echo "Echec lors de la connexion à MySQL : (" . self::$mysqli->connect_errno . ") " . self::$mysqli->connect_error;
		}
	}

	static function disconnect(){
		self::$mysqli->close();
	}

	static function select(){

	}

	static function insert(){

	}
}
?>