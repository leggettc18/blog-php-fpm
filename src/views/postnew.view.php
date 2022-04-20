<!DOCTYPE html>
<html>
    <head>
        <title>A blog application | New post</title>
        <?php require 'templates/head.php' ?>
    </head>
    <body>
        <?php require 'templates/top-menu.php' ?>
        <?php if (isset($_GET['post_id'])): ?>
            <h1>Edit post</h1>
        <?php else: ?>
            <h1>New post</h1>
        <?php endif ?>
        <?php if ($errors): ?>
            <div class="error box">
                <ul>
                    <?php foreach ($errors as $error): ?>
                        <li><?php echo $error ?></li>
                    <?php endforeach ?>
                </ul>
            </div>
        <?php endif ?>

        <form method="post" action="/posts/create" class="post-form user-form">
            <div>
                <label for="post-title">Title:</label>
                <input
                    id="post-title"
                    name="post-title"
                    type="text"
                    value="<?php echo htmlEscape($title) ?>"
                />
            </div>
            <div>
                <label for="post-body">Body:</label>
                <textarea
                    id="post-body"
                    name="post-body"
                    rows="12"
                    cols="70"
                ><?php echo htmlEscape($body) ?></textarea>
            </div>
            <div>
                <input
                    class="button-primary"
                    type="submit"
                    value="Save post"
                />
                <a href="/">Cancel</a>
            </div>
        </form>
    </body>
</html>