<?php

use leggettc18\SimpleORM\DataModel;

class Post extends leggettc18\SimpleORM\DataModel {

	protected static $createdAtColumn = 'created_at';
	protected static $updatedAtColumn = 'updated_at';
	
	/**
	 * Returns post list sorted in descending order
	 * by their created_at date.
	 * 
	 * @return array
	 */
	public static function allByDateDescending() {
		$posts = Post::all();
		usort($posts, 'static::dateCompareDesc');
		return $posts;
	}

	/**
	 * Compares two dates so that the more recent date wins
	 * 
	 * @param mixed any object with a created_at property
	 * @param mixed same type of object
	 * @return int can be used as boolean, true if $element1 is smaller than $element2, false if not
	 */
	static function dateCompareDesc($element1, $element2) {
		$datetime1 = strtotime($element1->created_at);
		$datetime2 = strtotime($element2->created_at);
		return $datetime2 - $datetime1;
	}

}

?>
