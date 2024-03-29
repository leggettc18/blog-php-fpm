<?php

namespace Blog\Lib;

use Blog\Models\User;
use PDO;
use DateTime;
use Exception;

class Common
{
    /**
     * Escapes HTML so it is safe to output
     * 
     * @param string $html
     * @return string
     */
    static function htmlEscape($html)
    {
        return htmlentities($html, ENT_QUOTES, 'UTF-8');
    }

    static function redirectAndExit($script)
    {
        // Get the domain-relative URL (e.g. /blog/whatever.php or /whatever.php) and work
        // out the folder (e.g. /blog/ or /).
        $relativeUrl = $_SERVER['REQUEST_URI'];
        $urlFolder = substr($relativeUrl, 0, strrpos($relativeUrl, '/') + 1);

        // Redirect to the full URL (http://myhost/blog/script.php)
        $host = $_SERVER['HTTP_HOST'];
        $fullUrl = 'http://' . $host . $urlFolder . $script;
        header('Location: ' . $fullUrl);
        exit();
    }

    static function view($name, $data = [])
    {
        extract($data);

        return require "../src/views/{$name}.view.php";
    }

    /**
     * Looks up the user_id for the current auth user
     * 
     * Throws exception if user is disabled.
     * 
     * @throws Exception
     * @return int
     */
    static function getAuthUserId()
    {
        // Reply with null if there is no logged-in user
        if (!static::isLoggedIn()) {
            return null;
        }

        $user = User::retrieveByUsername(static::getAuthUser(), User::FETCH_ONE);

        /*
    $sql = "
        SELECT
            id
        FROM
            user
        WHERE
            username = :username
            AND is_enabled = 1
    ";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(
        array(
            'username' => getAuthUser()
        )
    ); */

        if (!$user->is_enabled) {
            throw new Exception('The currently logged in User has been disabled!');
        }

        return $user->id;
    }

    /**
     * Gets the PDO object for database access
     * 
     * @return \PDO
     */
    static function getPDO()
    {
        $dsn = getDSN();
        $user = 'blog';
        $pass = 'blog-password';

        $options = [
            PDO::ATTR_ERRMODE               => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE    => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES      => false,
        ];

        try {
            $pdo = new PDO($dsn, $user, $pass, $options);
        } catch (\PDOException $e) {
            throw new \PDOException($e->getMessage(), (int)$e->getCode());
        }

        return $pdo;
    }

    /**
     * Converts SQL Dates to a more readable format
     * 
     * @param string $sqlDate
     * @return string
     */
    static function convertSqlDate($sqlDate)
    {
        /* @var $date DateTime */
        $date = DateTime::createFromFormat('Y-m-d H:i:s', $sqlDate);

        return $date->format('d M Y, H:i');
    }

    static function isLoggedIn()
    {
        return isset($_SESSION['logged_in_username']);
    }

    /**
     * Gets the root path of the project
     * 
     * @return string
     */
    static function getRootPath()
    {
        return realpath(__DIR__ . '/..');
    }

    static function getAuthUser()
    {
        return Common::isLoggedIn() ? $_SESSION['logged_in_username'] : null;
    }

    /**
     * Logs the user in
     * 
     * For safety, we ask PHP to regenerate the cookie, so if a user logs onto a site that a cracker
     * has prepared for him/her (e.g. on a public computer) the cracker's copy of the cookie ID will be
     * useless.
     * 
     * @param string $username
     */
    static function login($username)
    {
        session_regenerate_id();

        $_SESSION['logged_in_username'] = $username;
    }

    /**
     * Logs the user out
     */
    static function logout()
    {
        unset($_SESSION['logged_in_username']);
    }

    static function tryLogin($username, $password)
    {
        $user = User::retrieveByUsername($username, User::FETCH_ONE);
        $success = password_verify($password, $user->password);
        /* $sql = "
        SELECT
            password
        FROM
            user
        WHERE
            username = :username
            AND is_enabled = 1
    ";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(
        array('username' => $username, )
    );

    // Get the hash from this row, and use the third-party hashing library to check it
    $hash = $stmt->fetchColumn();
    $success = password_verify($password, $hash); */

        return $success;
    }
}

# $postModel = new Post($adapter);


/**
 * Gets the DSN for the MySQL Connection
 *
 * @return string
 */
function getDSN()
{
    $host = 'mariadb';
    $db = 'blog';
    $charset = 'utf8mb4';

    $dsn = "mysql:host=$host;dbname=$db;charset=$charset";

    return $dsn;
}

function getSqlDateForNow()
{
    return date('Y-m-d H:i:s');
}

/**
 * Gets a list of posts in reverse order
 * 
 * @param PDO $pdo
 * @return array
 *
function getAllPosts(PDO $pdo)
{
    $stmt = $pdo->query(
        'SELECT
            id, title, created_at, body,
            (SELECT COUNT(*) FROM comment WHERE comment.post_id = post.id) comment_count
        FROM
            post
        ORDER BY
            created_at DESC'
    );
    if ($stmt === false)
    {
        throw new Exception('There was a problem running this query');
    }

    return $stmt->fetchAll();
}
 */

/**
 * Converts unsafe text to safe, paragraphed, HTML
 * 
 * @param string $text
 * @return string
 */
function convertNewlinesToParagraphs($text)
{
    $escaped = Common::htmlEscape($text);

    return '<p>' . str_replace("\n", "</p><p>", $escaped) . '</p>';
}



/**
 * Returns all the comments for the specified post
 * 
 * @param PDO $pdo
 * @param integer $postId
 * return array
 */
/* function getCommentsForPost(PDO $pdo, $postId)
{
    $sql = "
        SELECT
            id, name, text, created_at, website
        FROM
            comment
        WHERE
            post_id = :post_id
    ";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(
        array('post_id' => $postId, )
    );

    return $stmt->fetchAll();
} */