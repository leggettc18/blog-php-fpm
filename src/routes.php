<?php

$router->get('', 'PagesController@home');
$router->get('posts', 'PostController@index');
$router->get('posts/new', 'PostController@new');
$router->post('posts/create', 'PostController@create');
$router->get('assets/main.css', 'assets/main.css');
$router->get('install', 'PagesController@install');
$router->post('install', 'PagesController@install');
$router->get('login', 'PagesController@login');
$router->post('login', 'PagesController@login');
$router->get('posts/show', 'PostController@show');
$router->get('logout', 'PagesController@logout');
$router->get('posts/edit', 'PostController@edit');
$router->post('posts/update', 'PostController@update');
$router->post('posts/delete', 'PostController@delete');
$router->post('comments/create', 'CommentController@create');
$router->post('comments/delete', 'CommentController@delete');
$router->get('xdebug', 'PagesController@xdebug');
