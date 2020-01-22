<?php

require_once 'interfaces/adapter.php'

class MySQLAdapter implements AdapterInterface
{
	protected PDO $pdo;

	public function __construct($host, $db, $charset, $user, $pass) {
		$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
		$options = [
        	PDO::ATTR_ERRMODE               => PDO::ERRMODE_EXCEPTION,
        	PDO::ATTR_DEFAULT_FETCH_MODE    => PDO::FETCH_ASSOC,
        	PDO::ATTR_EMULATE_PREPARES      => false,
    	];
    	try {
        	$this->pdo = new PDO($dsn, $user, $pass, $options);
    	} catch (\PDOException $e) {
        	throw new \PDOException($e->getMessage(), (int)$e->getCode());
    	}
	}

	public function runQuery($query, $arguments) {
		$stmt = $this->pdo.prepare($query);
		$stmt->execute($arguments);

		return $stmt->fetchAll();
	}
}

?>
