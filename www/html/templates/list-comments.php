<?php
/**
 * @var $pdo PDO
 * @var $postId integer
 * @var $commentCount integer
 */
?>
<h3><?php echo count($comments) ?> comments</h3>

<?php foreach ($comments as $comment): ?>
    <form
        action="/comments/delete"
        method="post"
        class="comment-list"
    >

        <div class="comment">
            <div class="comment-meta">
                Comment from
                <?php echo htmlEscape($comment->name) ?>
                on
                <?php echo convertSqlDate($comment->created_at) ?>
                <?php if (isLoggedIn()): ?>
                    <input type="hidden" id="comment-id" name="comment-id" value="<?php echo $comment->id ?>" />
                    <input
                        type="submit"
                        name="delete-comment"
                        value="Delete"
                    />
                <?php endif ?>
            </div>
            <div class="comment-body">
                <?php // This is already escaped ?>
                <?php echo convertNewlinesToParagraphs($comment->text) ?>
            </div>
        </div>
    </form>
<?php endforeach ?>