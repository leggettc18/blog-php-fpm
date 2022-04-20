<?php

use Pecee\SimpleRouter\SimpleRouter;

require '../vendor/autoload.php';

require_once '../src/core/bootstrap.php';
require_once '../src/routes.php';

session_start();

SimpleRouter::start();
?>