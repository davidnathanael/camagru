<?php
session_start();

include $_SERVER['DOCUMENT_ROOT'] . '/config/database.php';
include $_SERVER['DOCUMENT_ROOT'] . '/validations/DB_Utils.php';

if(( $_POST['username'] == "" || $_POST['password'] == ""))
	header("Location: ../auth.php?action=login&login=". $_POST['username'] ."&msg=" . urlencode('All fields are required'));
elseif( $_POST['form_token'] != $_SESSION['form_token'])
	header("Location: ../auth.php?action=login&msg=" . urlencode('Invalid form submission'));
else
{
    $username = filter_var($_POST['username'], FILTER_SANITIZE_STRING);
    $password = filter_var($_POST['password'], FILTER_SANITIZE_STRING);

    $password = hash('whirlpool', $password);

    try
    {
        $DB->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $stmt = $DB->prepare('SELECT * FROM users WHERE login = :login AND password = :password');

        $stmt->execute(array(
                ':login' => $username,
                ':password' => $password
        ));

        unset( $_SESSION['form_token'] );

		$data = $stmt->fetchAll(PDO::FETCH_ASSOC);
		$DB = null;

		if (!empty($data))
		{
			if ($data[0]['confirmed'])
			{
				$_SESSION['login'] = $data[0]['login'];
				$_SESSION['id'] = $data[0]['id'];
				$_SESSION['email'] = $data[0]['mail'];
				header("Location: ../index.php" . urlencode($message));
			}
			else
				header("Location: ../auth.php?action=login&login=" . $username . "&msg=" . urlencode("Please confirm your account"));
		}
		else
		{
			$message = "Incorrect login or password";
			header("Location: ../auth.php?action=login&msg=" . urlencode($message));
		}
    }
    catch(Exception $e)
    {
        $message = 'We are unable to process your request. Please try again later';
		header("Location: ../auth.php?action=login&msg=" . urlencode($message));
    }
}
?>
