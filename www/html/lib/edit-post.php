<?php

/**
 * Creates a new post and returns its ID
 * 
 * @param string
 * @param string
 * @param int
 * @return int
 */
function addPost($title, $body, $userId)
{
    $post = new Post();
    $post->title = $title;
    $post->body = $body;
    $post->user_id = $userId;
    $post->save();
    // Prepare the insert query
    /* $sql = "
        INSERT INTO
            post
            (title, body, user_id, created_at)
            VALUES
            (:title, :body, :user_id, :created_at)
    ";
    $stmt = $pdo->prepare($sql);
    if ($stmt === false)
    {
        throw new Exception('Could not prepare post insert query');
    } */

    // Now run the query, with these parameters
    /* $result = $stmt->execute(
        array(
            'title' => $title,
            'body' => $body,
            'user_id' => $userId,
            'created_at' => getSqlDateForNow(),
        )
    );
    if ($result === false)
    {
        throw new Exception('Could not run post insert query');
    } */

    return $post->id;
}

/**
 * Edits a post and returns true if it succeeds,
 * and throws an exception if it doesn't.
 * 
 * @param Post
 * @param string
 * @param string
 * @throws Exception
 * @return boolean
 */
function editPost($post, $title, $body)
{
    $post->title = $title;
    $post->body = $body;
    $post->save();
    // Prepare the update query
    /* $sql = "
        UPDATE
            post
        SET
            title = :title,
            body = :body
        WHERE
            id = :post_id
    ";
    $stmt = $pdo->prepare($sql);
    if ($stmt === false)
    {
        throw new Exception('Could not prepare post update query');
    } */

    // Now run the query, with these parameters
    /* $result = $stmt->execute(
        array(
            'title' => $title,
            'body' => $body,
            'post_id' => $postId,
        )
    );
    if ($result === false)
    {
        throw new Exception('Could not run post update query');
    } */

    return true;
}