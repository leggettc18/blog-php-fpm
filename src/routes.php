<?php

use Pecee\SimpleRouter\SimpleRouter;
use Blog\Controllers\PagesController;
use Blog\Controllers\PostController;
use Blog\Controllers\CommentController;

SimpleRouter::get('/', [PagesController::class, 'home']);
SimpleRouter::get('posts', [PostController::class, 'index']);
SimpleRouter::get('posts/new', [PostController::class, 'new']);
SimpleRouter::post('posts/create', [PostController::class, 'create']);
SimpleRouter::get('posts/show', [PostsController::class, 'show']);
SimpleRouter::get('posts/edit', [PostController::class, 'edit']);
SimpleRouter::post('posts/update', [PostController::class, 'update']);
SimpleRouter::post('posts/delete', [PostController::class, 'delete']);
SimpleRouter::post('comments/create', [CommentController::class, 'create']);
SimpleRouter::post('comments/delete', [CommentController::class, 'delete']);
SimpleRouter::get('install', [PagesController::class, 'install']);
SimpleRouter::post('install', [PagesController::class, 'install']);
SimpleRouter::get('login', [PagesController::class, 'login']);
SimpleRouter::post('login', [PagesController::class, 'login']);
Simplerouter::get('logout', [PagesController::class, 'logout']);
SimpleRouter::get('xdebug', [PagesController::class, 'xdebug']);

//$router->get('assets/main.css', 'assets/main.css');