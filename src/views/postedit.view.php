<!DOCTYPE html>
<html>
    <head>
        <title>A blog application | New post</title>
        <?php require '../src/templates/head.php' ?>
    </head>
    <body>
        <?php require '../src/templates/top-menu.php' ?>
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

        <form method="post" action="/posts/update" class="post-form user-form">
            <input id="post-id" name="post-id" type=hidden value="<?php echo $post->id ?>" />
            <div>
                <label for="post-title">Title:</label>
                <input
                    id="post-title"
                    name="post-title"
                    type="text"
                    value="<?php echo htmlEscape($post->title) ?>"
                />
            </div>
            <div>
                <label for="post-body">Body:</label>
                <textarea
                    id="post-body"
                    name="post-body"
                    rows="12"
                    cols="70"
                ><?php echo htmlEscape($post->body) ?></textarea>
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