<?php

require 'vendor/autoload.php';
require 'controllers/PagesController.php';
require 'controllers/PostController.php';
require 'controllers/CommentController.php';

require_once 'core/bootstrap.php';

use leggettc18\SimpleRouter\{Router, Request};

session_start();

Router::load('routes.php')
    ->direct(Request::uri(), Request::method());
?>