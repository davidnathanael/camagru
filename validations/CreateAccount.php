<?php
session_start();
include $_SERVER['DOCUMENT_ROOT'] . '/config/database.php';
if(!isset( $_POST['username'], $_POST['password'], $_POST['confirm_password'], $_POST['mail'], $_POST['form_token']))
    header("Location: ../auth.php?action=login&msg=" . urlencode('All fields are required'));
elseif( $_POST['form_token'] != $_SESSION['form_token'])
    header("Location: ../auth.php?action=login&msg=" . urlencode('Invalid form submission'));
elseif (strlen( $_POST['username']) > 20 || strlen($_POST['username']) < 4)
    header("Location: ../auth.php?action=login&msg=" . urlencode('Incorrect Length for Username'));
elseif (strlen( $_POST['password']) > 20 || strlen($_POST['password']) < 4)
    header("Location: ../auth.php?action=login&msg=" . urlencode('Incorrect Length for Password'));
elseif ($_POST['password'] != $_POST['confirm_password'])
    header("Location: ../auth.php?action=login&msg=" . urlencode('Passwords dont match'));
elseif (ctype_alnum($_POST['username']) != true)
    header("Location: ../auth.php?action=login&msg=" . urlencode('Username must be alpha numeric'));
elseif (ctype_alnum($_POST['password']) != true)
    header("Location: ../auth.php?action=login&msg=" . urlencode('Password must be alpha numeric'));
else
{
    /*** if we are here the data is valid and we can insert it into database ***/
    $username = filter_var($_POST['username'], FILTER_SANITIZE_STRING);
    $password = filter_var($_POST['password'], FILTER_SANITIZE_STRING);
    $mail = filter_var($_POST['mail'], FILTER_VALIDATE_EMAIL);
    date_default_timezone_set('Europe/Paris');
    $date = date("Y-m-d H:i:s");

    /*** now we can encrypt the password ***/
    $password = hash('whirlpool', $password);
    
    // /*** connect to database ***/ 
    // /*** mysql hostname ***/
    // $mysql_hostname = 'localhost';

    // /*** mysql username ***/
    // $mysql_username = 'mysql_username';

    // /*** mysql password ***/
    // $mysql_password = 'mysql_password';

    // /*** database name ***/
    // $mysql_dbname = 'auth';

    try
    {
        $dbh = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD);
        // ** $message = a message saying we have connected **

        // /*** set the error mode to excptions ***/
        $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        /*** prepare the insert ***/
        $stmt = $dbh->prepare('INSERT INTO users (login, password, mail, createdAt) VALUES (:username, :password, :mail, :createdAt)');

        /*** execute the prepared statement ***/
        $stmt->execute(array(
                ':username' => $username,
                ':password' => $password,
                ':mail' => $mail,
                ':createdAt' => $date
            ));

        /*** unset the form token session variable ***/
        unset( $_SESSION['form_token'] );
        $_SESSION['login'] = $username;

        header("Location: ../index.php");
        /*** if all is done, say thanks ***/
        // $message = 'New user added';
    }
    catch(Exception $e)
    {
        /*** check if the username already exists ***/
        if( $e->getCode() == 23000)
            $message = 'Login or email already exists';
        else
            $message = 'We are unable to process your request. Please try again later"';
        header("Location: ../auth.php?action=signup&msg=" . urlencode($message));
    }
}
?>
