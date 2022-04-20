<?php

use Blog\Models\Post;

$host = 'mariadb';
$db = 'blog';
$charset = 'utf8mb4';
$user = 'blog';
$pass = 'blog-password';

Post::createConnection($host, $user, $pass, $db, array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES \'UTF8\''), 3306, $charset);
