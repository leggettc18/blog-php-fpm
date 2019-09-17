<nav class="top-menu">
	<span class="navbar-toggle" id="js-navbar-toggle">
		<i class="fas fa-bars"></i>
	</span>
	<a class="logo" href="index.php">logo</a>
    <ul class="menu-options" id="js-menu">
        <?php if (isLoggedIn()): ?>
        	<li>
            	<a class="nav-links" href="index.php">Home</a>
            </li>
            <li>
            	<a class="nav-links" href="list-posts.php">All posts</a>
            </li>
            <li>
            	<a class="nav-links" href="edit-post.php">New post</a>
            </li>
            <li>
            	<a class="nav-links" href="#">Hello <?php echo htmlEscape(getAuthUser()) ?>!</a>
            </li>
            <li>
            	<a class="nav-links" href="logout.php">Log out</a>
            </li>
        <?php else: ?>
        	<li>
            	<a class="nav-links" href="login.php">Log in</a>
            </li>
        <?php endif ?>
    </ul>
</nav>
