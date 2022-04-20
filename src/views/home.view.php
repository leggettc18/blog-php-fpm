<!DOCTYPE html>
<html>

<head>
    <title>A blog application</title>
    <?php require '../src/templates/head.php' ?>
</head>

<body id="main-grid">
    <?php require '../src/templates/title.php' ?>
    <?php if ($notFound) { ?>
        <div class="error box">
            Error: cannot find the requested blog post
        </div>
    <?php } ?>
    <div class="post-list">
        <?php foreach ($posts as $post) : ?>
            <div class="post-synopsis">
                <h2>
                    <?php echo \Blog\Lib\Common::htmlEscape($post->title) ?>
                </h2>
                <div class="meta">
                    <?php echo \Blog\Lib\Common::convertSqlDate($post->created_at) ?>

                    (<?php echo \Blog\Models\Comment::countByPostId($post->id) ?> comments)
                </div>
                <p>
                    <?php echo \Blog\Lib\Common::htmlEscape($post->body) ?>
                </p>
                <div class="post-controls">
                    <a href="/posts/show?post_id=<?php echo $post->id ?>">Read more...</a>
                    <?php if (\Blog\Lib\Common::isLoggedIn()) : ?>
                        |
                        <a href="/posts/edit?post_id=<?php echo $post->id ?>">Edit</a>
                    <?php endif ?>
                </div>
            </div>
        <?php endforeach ?>
    </div>
</body>

</html>