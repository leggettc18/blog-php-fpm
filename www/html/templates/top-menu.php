<div class="top-menu">
    <div class="menu-options">
        <?php if (isLoggedIn()): ?>
            <a class="button-primary" href="index.php">Home</a>
            <a class="button-primary" href="list-posts.php">All posts</a>
            |
            <a class="button-primary" href="edit-post.php">New post</a>
            |
            Hello <?php echo htmlEscape(getAuthUser()) ?>
            <a class="button-primary" href="logout.php">Log out</a>
        <?php else: ?>
            <a class="button-primary" href="login.php">Log in</a>
        <?php endif ?>
    </div>
</div>