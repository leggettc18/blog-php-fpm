<?php

require_once 'datamodel.php';

use leggettc18\SimpleORM\DataModel;

class Post extends leggettc18\SimpleORM\DataModel {

	protected static $createdAtColumn = 'created_at';
	protected static $updatedAtColumn = 'updated_at';

}

?>
