<nav id="js-top-menu" class="top-menu">
    <span class="navbar-toggle" id="js-navbar-toggle">
        <i class="fas fa-bars"></i>
    </span>
    <a class="logo" href="/">logo</a>
    <ul class="menu-options" id="js-menu">
        <?php if (\Blog\Lib\Common::isLoggedIn()) : ?>
            <li>
                <a class="nav-links" href="/">Home</a>
            </li>
            <li>
                <a class="nav-links" href="/posts">All posts</a>
            </li>
            <li>
                <a class="nav-links" href="/posts/new">New post</a>
            </li>
            <li>
                <a class="nav-links" href="#">Hello <?php echo \Blog\Lib\Common::htmlEscape(\Blog\Lib\Common::getAuthUser()) ?>!</a>
            </li>
            <li>
                <a class="nav-links" href="/logout">Log out</a>
            </li>
        <?php else : ?>
            <li>
                <a class="nav-links" href="/login">Log in</a>
            </li>
        <?php endif ?>
    </ul>
</nav>