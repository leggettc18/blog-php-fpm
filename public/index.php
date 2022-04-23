<?php

require '../vendor/autoload.php';

require_once '../src/core/bootstrap.php';

use leggettc18\SimpleRouter\{Router, Request};

session_start();

Router::load('../src/routes.php')
    ->direct(Request::uri(), Request::method());
