<?php

require_once 'interfaces/adapter.php';
require_once 'config/db.php';

class Database {

	protected $adapter;

	public function __construct(AdapterInterface $adapter) {
		$this->adapter = $adapter;
	}

	public function select($attributes, $table, $where, $orderby) {
		return $this->adapter->select($attributes, $table, $where, $orderby);
	}

	public function count($attributes, $table, $where) {
		return $this->adapter->count($attributes, $table, $where);
	}
}

?>
