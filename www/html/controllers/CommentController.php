<?php

class CommentController {
    public function create() {
        $comment = new Comment();
        $comment->post_id = $_POST['post_id'];
        $comment->name = $_POST['comment-name'];
        $comment->website = $_POST['comment-website'];
        $comment->text = $_POST['comment-text'];
        $comment->save();

        header("Location: /posts/show?post_id=$comment->post_id");
    }

    public function delete() {
        $comment = Comment::retrieveByPK($_POST['comment-id']);
        $postId = $comment->post_id;
        $comment->delete();

        header("Location: /posts/show?post_id=$postId");
    }
}