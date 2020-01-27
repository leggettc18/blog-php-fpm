<?php


class DBConfig {

	static $type = "mysql";
	static $host = '127.0.0.1';
	static $db = 'blog';
	static $charset = 'utf8mb4';
	static $user = 'blog';
	static $pass = 'blog';

	public static function getAdapter() {
		switch (static::$type) {
			case "mysql":
				$dbconn = new MySQLAdapter(static::$host, static::$db, static::$charset, static::$user, static::$pass);
				break;
			default:
				die("Invalid Database Type");
		}

		return $dbconn;
	}

}

?>
