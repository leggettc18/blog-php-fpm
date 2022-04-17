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

                        (<?php echo Comment::countByPostId($post->id) ?> comments)
                    </div>
                    <p>
                        <?php echo htmlEscape($post->body) ?>
                    </p>
                    <div class="post-controls">
                        <a href="/posts/show?post_id=<?php echo $post->id ?>"
                        >Read more...</a>
                        <?php if (isLoggedIn()): ?>
                            |
                            <a
                                href="/posts/edit?post_id=<?php echo $post->id ?>"
                            >Edit</a>
                        <?php endif ?>
                    </div>
                </div>
            <?php endforeach ?>
        </div>
    </body>
</html>
