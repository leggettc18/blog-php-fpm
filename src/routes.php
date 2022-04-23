<?php

$router->get('', '\Blog\Controllers\PagesController@home');
$router->get('posts', '\Blog\Controllers\PostController@index');
$router->get('posts/new', '\Blog\Controllers\PostController@new');
$router->post('posts/create', '\Blog\Controllers\PostController@create');
$router->get('assets/main.css', 'assets/main.css');
$router->get('install', '\Blog\Controllers\PagesController@install');
$router->post('install', '\Blog\Controllers\PagesController@install');
$router->get('login', '\Blog\Controllers\PagesController@login');
$router->post('login', '\Blog\Controllers\PagesController@login');
$router->get('posts/show', '\Blog\Controllers\PostController@show');
$router->get('logout', '\Blog\Controllers\PagesController@logout');
$router->get('posts/edit', '\Blog\Controllers\PostController@edit');
$router->post('posts/update', '\Blog\Controllers\PostController@update');
$router->post('posts/delete', '\Blog\Controllers\PostController@delete');
$router->post('comments/create', '\Blog\Controllers\CommentController@create');
$router->post('comments/delete', '\Blog\Controllers\CommentController@delete');
$router->get('xdebug', '\Blog\Controllers\PagesController@xdebug');
