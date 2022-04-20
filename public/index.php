<?php

use Pecee\SimpleRouter\SimpleRouter;
use Blog\Controllers\PagesController;

require '../vendor/autoload.php';
require '../src/Controllers/PagesController.php';
require '../src/Controllers/PostController.php';
require '../src/Controllers/CommentController.php';

require_once '../src/core/bootstrap.php';

session_start();

SimpleRouter::get('/', [PagesController::class, 'home']);
SimpleRouter::start();
