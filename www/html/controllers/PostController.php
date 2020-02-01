<?php

class PostController {
    public function index() {
        $posts = Post::all();

        return view('postindex', array('posts' => $posts));
    }

    public function show() {
        $post = Post::retrieveByPK($_GET['post_id']);

        return view('postshow', array('post' => $post));
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

        header("Location: /");
    }
}