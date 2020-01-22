<?php

require_once 'interfaces/adapter.php'
class Database {
	protected adapter;

	public function __construct(AdapterInterface adapter) {
		$this->adapter = adapter;
	}

	function select($attributes, $table, $conditions) {
		$sql = "
			SELECT :attributes
			FROM :table
			WHERE :conditions
		"
		$options = array( 'attributes' => implode(',', $attributes),
							'table' => $table,
							'conditions' => implode(' AND ', $conditions) );
		return $this->adapter.runQuery($sql, $options);
	}
}

?>
