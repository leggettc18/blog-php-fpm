<?php 
require_once 'lib/common.php';
require_once 'lib/list-posts.php';

session_start();

// Don't let non-auth users see this screen
if (!isLoggedIn())
{
    redirectAndExit('index.php');
}

if ($_POST)
{
    $deleteResponse = $_POST['delete-post'];
    if ($deleteResponse)
    {
        $keys = array_keys($deleteResponse);
        $deletePostId = $keys[0];
        if ($deletePostId)
        {
            deletePost($deletePostId);
            redirectAndExit('list-posts.php');
        }
    }
}

// Connect to the database, run a query
$pdo = getPDO();
$posts = Post::all();
?>
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
                                    href="view-post.php?post_id=<?php echo $post->id?>"
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