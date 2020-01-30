<?php

/**
 * Blog installer function
 * 
 * @return array(count array, error string)
 */
function installBlog(PDO $pdo)
{
    //Grab the SQL commands we want to run on the databse
    $root = getrootPath();

    $sql = file_get_contents($root . '/data/init.sql');

    $error = '';

    if ($sql === false)
    {
        $error = 'Cannot find SQL file';
    }

    if (!$error)
    {
        $result = $pdo->exec($sql);

        if ($result === false)
        {
            $error = 'Could not run SQL: ' . print_r($pdo->errorInfo(), true);
        }
    }

    // See how many rows we created, if any
    $count = array();

    foreach(array('post', 'comment') as $tableName)
    {
        if (!$error)
        {
            $sql = "SELECT COUNT(*) AS c FROM $tableName";

            $stmt = $pdo->query($sql);
            if ($stmt)
            {
                // We store each count in an associative array
                $count[$tableName] = $stmt->fetchColumn();
            }
        }
    }

    return array($count, $error);
}

/**
 * Updates the admin user in the database
 * 
 * @param string $username
 * @param integer $length
 * @return array Duple of (password, error)
 */
function createUser($username, $length = 10)
{
    // This algorithm creates a random password
    $alphabet = range(ord('A'), ord('z'));
    $alphabetLength = count($alphabet);

    $password = '';
    for($i = 0; $i < $length; $i++)
    {
        $letterCode = $alphabet[rand(0, $alphabetLength - 1)];
        $password .= chr($letterCode);
    }

    $error = '';

    $hash = password_hash($password, PASSWORD_DEFAULT);
    if ($hash === false)
    {
        $error = 'Password hashing failed';
    }

    if (!$error) {
        $user = User::retrieveByUsername($username, User::FETCH_ONE);
        $user->username = $username;
        $user->password = $hash;
        $user->is_enabled = true;
        $user->save();
    }

    // Insert the credentials into the database
    /* $sql = "
        UPDATE
            user
        SET
            password = :password, created_at = :created_at, is_enabled = 1
        WHERE
            username = :username
    ";
    $stmt = $pdo->prepare($sql);
    if ($stmt === false)
    {
        $error = 'Could not prepare the user update';
    }

    if (!$error)
    {
        // Create a hash of the password, to make a stolen user database (nearly) worthless.
        $hash = password_hash($password, PASSWORD_DEFAULT);
        if ($hash === false)
        {
            $error = 'Password hashing failed';
        }
    }
    
    //Insert user details, including hashed password
    if (!$error)
    {
        $result = $stmt->execute(
            array(
                'username' => $username,
                'password' => $hash,
                'created_at' => getSqlDateForNow(),
            )
        );
        if ($result === false)
        {
            $error = 'Could not run the user password update';
        }
    } */

    if ($error)
    {
        $password = '';
    }

    return array($password, $error);
}
?>