<?php
/**
 * Tries to delete the specified post
 * 
 * Deletes associated comments first
 * 
 * @param integer $postId
 * @return void
 * @throws Exception
 */
function deletePost($postId)
{
    // Delete comments first to avoid orphaned comments in the database.
    $comments = Comment::retrieveByPostId($postId);
    foreach ($comments as $comment) {
        $comment->delete();
    }
    // Now that comments are deleted, we can delete the post.
    $post = Post::retrieveByPK($postId);
    $post->delete();
    /* $sqls = array(
        // Delete comments first, to remove the foreign key objection
        "DELETE FROM
            comment
        WHERE
            post_id = :id",
        // Now we can delete the post
        "DELETE FROM
            post
        WHERE
            id = :id",
    );

    foreach ($sqls as $sql)
    {
        $stmt = $pdo->prepare($sql);
        if ($stmt === false)
        {
            throw new Exception('There was a problem preparing this query');
        }

        $result = $stmt->execute(
            array('id' => $postId, )
        ); */

        // Don't continue if something went wrong
        /* if ($result === false)
        {
            break;
        } 
    } */
}