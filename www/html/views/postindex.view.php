<!DOCTYPE html>
<html>
    <head>
        <title>A blog application | Blog posts</title>
        <?php require 'templates/head.php' ?>
    </head>
    <body>
        <?php require 'templates/top-menu.php' ?>

        <h1>Post list</h1>

        <p>You have <?php echo count($posts) ?> posts.</p>

        <form method="post">
            <table id="post-list">
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Creation date</th>
                        <th>Comments</th>
                        <th />
                        <th />
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($posts as $post): ?>
                        <tr>
                            <td>
                                <a 
                                    href="posts/show?post_id=<?php echo $post->id?>"
                                ><?php echo htmlEscape($post->title) ?></a>
                            </td>
                            <td>
                                <?php echo convertSqlDate($post->created_at) ?>
                            </td>
                            <td>
                                <?php echo Comment::countByPostId($post->id) ?>
                            </td>
                            <td>
                                <a class="button-primary" href="edit-post.php?post_id=<?php echo $post->id?>">Edit</a>
                            </td>
                            <td>
                                <input
                                    class="button-primary"
                                    type="submit"
                                    name="delete-post[<?php echo $post->id?>]"
                                    value="Delete"
                                />
                            </td>
                        </tr>
                    <?php endforeach ?>
                </tbody>
            </table>
        </form>
    </body>
</html>