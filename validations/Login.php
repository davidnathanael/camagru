<?php
session_start();

include $_SERVER['DOCUMENT_ROOT'] . '/config/database.php';

if(!isset( $_POST['username'], $_POST['password'], $_POST['form_token']))
	header("Location: ../auth.php?action=login&msg=" . urlencode('All fields are required'));
else if(( $_POST['username'] == "" || $_POST['password'] == "" || $_POST['form_token'] == ""))
    header("Location: ../auth.php?action=login&msg=" . urlencode('All fields are required'));
elseif( $_POST['form_token'] != $_SESSION['form_token'])
	header("Location: ../auth.php?action=login&msg=" . urlencode('Invalid form submission'));
else
{
    /*** if we are here the data is valid and we can insert it into database ***/
    $username = filter_var($_POST['username'], FILTER_SANITIZE_STRING);
    $password = filter_var($_POST['password'], FILTER_SANITIZE_STRING);

    /*** now we can encrypt the password ***/
    $password = hash('whirlpool', $password);
    
    try
    {
        $dbh = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD);
        // ** $message = a message saying we have connected **

        // /*** set the error mode to excptions ***/
        $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        /*** prepare the insert ***/
        $stmt = $dbh->prepare('SELECT * FROM users WHERE login = :login AND password = :password');

        /*** execute the prepared statement ***/
        $stmt->execute(array(
                ':login' => $username,
                ':password' => $password
        ));

        unset( $_SESSION['form_token'] );

		$data = $stmt->fetchAll(PDO::FETCH_ASSOC);

		if ($data)
		{
			$_SESSION['login'] = $data[0][login];
			header("Location: ../index.php" . urlencode($message));
		}
		else
		{
			$message = "Incorrect login or password";
			header("Location: ../auth.php?action=login&msg=" . urlencode($message));
		}
    }
    catch(Exception $e)
    {
        /*** check if the username already exists ***/
        if( $e->getCode() == 23000)
        {
            $message = 'Username already exists';
        }
        else
        {
            /*** if we are here, something has gone wrong with the database ***/
            $message = 'We are unable to process your request. Please try again later"';
        }
		header("Location: ../auth.php?action=login&msg=" . urlencode($message));
    }
}
?>
