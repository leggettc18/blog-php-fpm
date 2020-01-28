<?php

session_start();

require_once 'lib/common.php';

// Connect to the database, run a query, handle errors

$host = '127.0.0.1';
$db = 'blog';
$charset = 'utf8mb4';
$user = 'blog';
$pass = 'blog';

Post::createConnection($host, $user, $pass, $db, array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES \'UTF8\'', 3306, $charset));

$posts = Post::allByDateDescending();

$notFound = isset($_GET['not-found']);
?>

<!DOCTYPE html>
<html>
    <head>
        <title>A blog application</title>
        <?php require 'templates/head.php' ?>
    </head>
    <body id="main-grid">
        <?php require 'templates/title.php' ?>
        <?php if ($notFound): ?>
            <div class="error box">
                Error: cannot find the requested blog post
            </div>
        <?php endif ?>
        <div class="post-list">
            <?php foreach ($posts as $post): ?>
                <div class="post-synopsis">
                    <h2>
                        <?php echo htmlEscape($post->title) ?>
                    </h2>
                    <div class="meta">
                        <?php echo convertSqlDate($post->created_at) ?>

                        (<?php echo $post->getCommentCount() ?> comments)
                    </div>
                    <p>
                        <?php echo htmlEscape($post->body) ?>
                    </p>
                    <div class="post-controls">
                        <a href="view-post.php?post_id=<?php echo $post->id ?>"
                        >Read more...</a>
                        <?php if (isLoggedIn()): ?>
                            |
                            <a
                                href="edit-post.php?post_id=<?php echo $post->id ?>"
                            >Edit</a>
                        <?php endif ?>
                    </div>
                </div>
            <?php endforeach ?>
        </div>
    </body>
</html>
