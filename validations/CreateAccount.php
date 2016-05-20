<?php
session_start();

include $_SERVER['DOCUMENT_ROOT'] . '/config/database.php';
include $_SERVER['DOCUMENT_ROOT'] . '/validations/Utils.php';
include $_SERVER['DOCUMENT_ROOT'] . '/validations/DB_Utils.php';

if(!isset( $_POST['username'], $_POST['password'], $_POST['confirm_password'], $_POST['mail'], $_POST['form_token']))
    header("Location: ../auth.php?action=signup&login=" . $_POST['username'] . "&mail=". $_POST['mail'] ."&msg=" . urlencode('All fields are required'));
if($_POST['username'] == "" || $_POST['password'] == "" || $_POST['confirm_password'] == "" || $_POST['mail'] == "" || $_POST['form_token'] == "")
    header("Location: ../auth.php?action=signup&login=" . $_POST['username'] . "&mail=". $_POST['mail'] ."&msg=" . urlencode('All fields are required'));
elseif( $_POST['form_token'] != $_SESSION['form_token'])
    header("Location: ../auth.php?action=signup&msg=" . urlencode('Invalid form submission'));
elseif (strlen( $_POST['username']) > 20 || strlen($_POST['username']) < 4)
    header("Location: ../auth.php?action=signup&msg=" . urlencode('Incorrect length for username : minimum 4 caracters'));
elseif (strlen( $_POST['password']) > 20 || strlen($_POST['password']) < 4)
    header("Location: ../auth.php?action=signup&msg=" . urlencode('Incorrect length for password : minimum 4 caracters'));
elseif ($_POST['password'] != $_POST['confirm_password'])
    header("Location: ../auth.php?action=signup&msg=" . urlencode('Passwords dont match'));
elseif (ctype_alnum($_POST['username']) != true)
    header("Location: ../auth.php?action=signup&msg=" . urlencode('Username must be alpha numeric'));
elseif (ctype_alnum($_POST['password']) != true)
    header("Location: ../auth.php?action=signup&msg=" . urlencode('Password must be alpha numeric'));
else
{
    $username = filter_var($_POST['username'], FILTER_SANITIZE_STRING);
    $password = filter_var($_POST['password'], FILTER_SANITIZE_STRING);
    $mail = filter_var($_POST['mail'], FILTER_VALIDATE_EMAIL);
    date_default_timezone_set('Europe/Paris');

    $password = hash('whirlpool', $password);
    $confirmation_hash = generateRandomString(50);

    try
    {
        $stmt = $DB->prepare('INSERT INTO users (login, password, mail, confirmation_hash) VALUES (:username, :password, :mail, :confirmation_hash)');

        $stmt->execute(array(
                ':username' => $username,
                ':password' => $password,
                ':mail' => $mail,
                ':confirmation_hash' => $confirmation_hash,
        ));

        $DB = null;
        unset( $_SESSION['form_token'] );
        $confirmation_link = "http://" . $_SERVER[HTTP_HOST] . "/validations/ConfirmAccount.php?hash=" . $confirmation_hash;

        $mail_message = "Thank you for signing up. To verify your account please click this link : " . $confirmation_link . " or enter the following code : " . $confirmation_hash;

        sendMail($mail, "Confirm your account", $mail_message);

        header("Location: ../auth.php?action=login&login=". $username ."&msg=" . urlencode('Your account has been created, a confirmation link has been sent to your email.'));

    }
    catch(Exception $e)
    {
        if( $e->getCode() == 23000)
            $message = 'Login or email already exists';
        else
            $message = 'We are unable to process your request. Please try again later';
        header("Location: ../auth.php?action=signup&msg=" . urlencode($message . $e->getMessage()));
    }
}
?>
