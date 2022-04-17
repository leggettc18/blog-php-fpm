<!DOCTYPE html>
<html>
    <head>
        <title>
            A blog application | 
            <?php echo htmlEscape($post->title) ?>
        </title>
        <?php require 'templates/head.php' ?>
    </head>
    <body>
        <?php require 'templates/title.php' ?>
        <div class="post">
            <h2>
                <?php echo htmlEscape($post->title) ?>
            </h2>
            <div class="date">
                <?php echo convertSqlDate($post->created_at) ?>
            </div>
            <?php echo convertNewlinesToParagraphs($post->body) ?>
        </div>
        <?php require 'templates/list-comments.php' ?>
        <?php // We use $commentData in this HTML fragment ?>
        <?php require 'templates/comment-form.php' ?>
    </body>
</html>