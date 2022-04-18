<?php

require '../models/post.php';
require '../lib/common.php';

class PagesController
{

    public function home()
    {
        $posts = Post::allByDateDescending();

        return view('home', array('posts' => $posts));
    }

    public function install()
    {
        return require '../install.php';
    }

    public function login()
    {
        return require '../login.php';
    }

    public function logout()
    {
        return require '../logout.php';
    }
}
