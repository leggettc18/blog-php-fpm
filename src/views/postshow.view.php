<!DOCTYPE html>
<html>

<head>
    <title>
        A blog application |
        <?php echo htmlEscape($post->title) ?>
    </title>
    <?php require '../src/templates/head.php' ?>
</head>

<body>
    <?php require '../src/templates/title.php' ?>
    <div class="post">
        <h2>
            <?php echo htmlEscape($post->title) ?>
        </h2>
        <div class="date">
            <?php echo convertSqlDate($post->created_at) ?>
        </div>
        <?php echo convertNewlinesToParagraphs($post->body) ?>
    </div>
    <?php require '../src/templates/list-comments.php' ?>
    <?php // We use $commentData in this HTML fragment 
    ?>
    <?php require '../src/templates/comment-form.php' ?>
</body>

</html>