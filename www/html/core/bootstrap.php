<?php

require_once 'models/post.php';

$host = '127.0.0.1';
$db = 'blog';
$charset = 'utf8mb4';
$user = 'blog';
$pass = 'blog';

Post::createConnection($host, $user, $pass, $db, array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES \'UTF8\'', 3306, $charset));