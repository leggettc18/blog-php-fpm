<?php

use Pecee\SimpleRouter\SimpleRouter;

SimpleRouter::setDefaultNamespace('\Blog\Controllers');

SimpleRouter::get('/', 'PagesController@home');
SimpleRouter::get('posts', 'PostController@index');
SimpleRouter::get('posts/new', 'PostController@new');
SimpleRouter::post('posts/create', 'PostController@create');
SimpleRouter::get('posts/show', 'PostController@show');
SimpleRouter::get('posts/edit', 'PostController@edit');
SimpleRouter::post('posts/update', 'PostController@update');
SimpleRouter::post('posts/delete', 'PostController@delete');
SimpleRouter::post('comments/create', 'CommentController@create');
SimpleRouter::post('comments/delete', 'CommentController@create');
SimpleRouter::get('install', 'PagesController@install');
SimpleRouter::post('install', 'PagesController@install');
SimpleRouter::get('login', 'PagesController@login');
SimpleRouter::post('login', 'PagesController@login');
Simplerouter::get('logout', 'PagesController@logout');
SimpleRouter::get('xdebug', 'PagesController@xdebug');
