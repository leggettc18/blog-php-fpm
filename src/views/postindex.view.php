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
                                    href="/posts/show?post_id=<?php echo $post->id?>"
                                ><?php echo htmlEscape($post->title) ?></a>
                            </td>
                            <td>
                                <?php echo convertSqlDate($post->created_at) ?>
                            </td>
                            <td>
                                <?php echo Comment::countByPostId($post->id) ?>
                            </td>
                            <td>
                                <a class="button-primary" href="/posts/edit?post_id=<?php echo $post->id?>">Edit</a>
                            </td>
                            <td>
                                <form method="post" action="/posts/delete">
                                    <input type="hidden" name="post-id" value="<?php echo $post->id?>" />
                                    <input
                                        class="button-primary"
                                        type="submit"
                                        name="delete-post"
                                        value="Delete"
                                    />
                                </form>
                            </td>
                        </tr>
                    <?php endforeach ?>
                </tbody>
            </table>
        </form>
    </body>
</html>