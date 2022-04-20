<?php
use Blog\Lib\Common;
use Blog\Lib\Install;
// We store stuff in the session, to survive the redirect to self
session_start();

// Only run the installer when we're responding to the form
if ($_POST) {
    //Here's the install
    $pdo = Common::getPDO();
    list($rowCounts, $error) = Install::installBlog($pdo);

    $password = '';
    if (!$error) {
        $username = 'admin';
        list($password, $error) = Install::createUser($username);
    }

    $_SESSION['count'] = $rowCounts;
    $_SESSION['error'] = $error;
    $_SESSION['username'] = $username;
    $_SESSION['password'] = $password;
    $_SESSION['try-install'] = true;

    // ... and here we redirect from POST to GET
    Common::redirectAndExit('install');
}

// Let's see if we've just installed
$attempted = false;
if (isset($_SESSION['try-install'])) {
    $attempted = true;
    $count = $_SESSION['count'];
    $error = $_SESSION['error'];
    $username = $_SESSION['username'];
    $password = $_SESSION['password'];

    // Unset session variables, so we only report the install/failure once
    unset($_SESSION['count']);
    unset($_SESSION['error']);
    unset($_SESSION['username']);
    unset($_SESSION['password']);
    unset($_SESSION['try-install']);
}
?>
<!DOCTYPE html>
<html>

<head>
    <title>Blog installer</title>
    <?php require '../src/templates/head.php' ?>
</head>

<body>
    <?php if ($attempted) : ?>
        <?php if ($error) : ?>
            <div class="error box">
                <?php echo $error ?>
            </div>
        <?php else : ?>
            <div class="success box">
                The database and demo data was created OK.
                <?php // Report the counts for each table 
                ?>
                <?php foreach (array('post', 'comment') as $tableName) : ?>
                    <?php if ($count) : ?>
                        <?php echo $count[$tableName] ?> new
                        <?php echo $tableName ?>s were created.
                    <?php endif ?>
                <?php endforeach ?>
                <?php // Report the new password 
                ?>
                The new '<?php echo Common::htmlEscape($username) ?>' password is
                <span class="install-password"><?php echo Common::htmlEscape($password) ?></span>
                (copy it to clipboard if you wish).
            </div>
            <p>
                <a href="/">View the blog</a>,
                or <a href="/install">install again</a>.
            </p>
        <?php endif ?>
    <?php else : ?>
        <p>Click the install button to reset the database.</p>
        <form method="post">
            <input class="button-primary" name="install" type="submit" value="Install" />
        </form>
    <?php endif ?>
</body>

</html>