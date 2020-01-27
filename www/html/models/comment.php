<?php

require_once 'datamodel.php';

use leggettc18\SimpleORM\DataModel;

class Comment extends leggettc18\SimpleORM\DataModel {

    public static function getCommentCount($postId) {
        return static::count("SELECT COUNT(*) FROM comment WHERE comment.post_id = $postId");
    }
}

?>