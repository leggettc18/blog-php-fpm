<?php

class PostController {
    public function index() {
        $posts = Post::all();

        return view('postindex', array('posts' => $posts));
    }

    public function show() {
        $post = Post::retrieveByPK($_GET['post_id']);
        $comments = Comment::retrieveByPostId($_GET['post_id']);

        return view('postshow', array('post' => $post, 'comments' => $comments));
    }

    public function new() {
        return view('postnew');
    }

    public function create() {
        $post = new Post();
        $post->title = $_POST['post-title'];
        $post->body = $_POST['post-body'];
        $post->user_id = getAuthUserId();
        $post->save();

        header("Location: /posts/show?post_id=$post->id");
    }

    public function edit() {
        $post = Post::retrieveByPK($_GET['post_id']);
        return view('postedit', array('post' => $post));
    }

    public function update() {
        $post = Post::retrieveByPK($_POST['post-id']);
        $post->title = $_POST['post-title'];
        $post->body = $_POST['post-body'];
        $post->save();

        header("Location: /posts/show?post_id=$post->id");
    }

    public function delete() {
        $post = Post::retrieveByPK($_POST['post-id']);
        $comments = Comment::retrieveByPostId($_POST['post-id']);
        foreach ($comments as $comment) {
            $comment->delete();
        }
        $post->delete();

        header("Location: /posts");
    }
}