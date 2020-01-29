<?php

use leggettc18\SimpleORM\DataModel;

class Post extends leggettc18\SimpleORM\DataModel {

	protected static $createdAtColumn = 'created_at';
	protected static $updatedAtColumn = 'updated_at';
	
	public static function allByDateDescending() {
		$posts = Post::all();
		usort($posts, 'static::dateCompareDesc');
		return $posts;
	}

	static function dateCompareDesc($element1, $element2) {
		$datetime1 = strtotime($element1->created_at);
		$datetime2 = strtotime($element2->created_at);
		return $datetime2 - $datetime1;
	}

}

?>
