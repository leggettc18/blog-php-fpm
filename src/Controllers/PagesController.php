<?php

namespace Blog\Controllers;

use Blog\Models\Post;
use Blog\Lib\Common;

class PagesController
{

    public function xdebug()
    {
        return require '../src/xdebug.php';
    }

    public function home()
    {
        $posts = Post::allByDateDescending();

        return Common::view('home', array('posts' => $posts));
    }

    public function install()
    {
        return require '../src/install.php';
    }

    public function login()
    {
        return require '../src/login.php';
    }

    public function logout()
    {
        return require '../src/logout.php';
    }
}
