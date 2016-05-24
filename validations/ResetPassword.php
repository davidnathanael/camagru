<?php

session_start();

include $_SERVER['DOCUMENT_ROOT'] . '/config/database.php';
include $_SERVER['DOCUMENT_ROOT'] . '/validations/DB_Utils.php';

if(( $_POST['password'] == "" || $_POST['confirm_password'] == ""))
	header("Location: ../auth.php?action=reset_password&msg=" . urlencode('All fields are required'));
else if(( $_POST['hash'] == ""))
	header("Location: ../auth.php?action=reset_password&msg=" . urlencode('Invalid hash.'));
elseif( $_POST['password'] != $_POST['confirm_password'])
	header("Location: ../auth.php?action=reset_password&hash=". $_POST['hash'] ."&msg=" . urlencode('Passwords dont match.'));
elseif( $_POST['form_token'] != $_SESSION['form_token'])
	header("Location: ../auth.php?action=reset_password&hash=". $_POST['hash'] ."&msg=" . urlencode('Invalid form token'));
else if (strlen( $_POST['password']) > 20 || strlen($_POST['password']) < 4)
    header("Location: ../auth.php?action=reset_password&hash=". $_POST['hash'] ."&msg=" . urlencode('Incorrect length for password min : 4 caracters max : 20 caracters'));
else if (!preg_match("(^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).+$)", $_POST['password']))
    header("Location: ../auth.php?action=reset_password&hash=". $_POST['hash'] ."&msg=" . urlencode('Password must contain at least one lowercase, one uppercase and one number'));
else
{
    $password = filter_var($_POST['password'], FILTER_SANITIZE_STRING);

    $hashed_password = hash('whirlpool', $password);
    $resetpw_hash = $_POST['hash'];

    try
    {
        $stmt = $DB->prepare('SELECT * FROM users WHERE resetpw_hash = :resetpw_hash');

        $stmt->execute(array(
                ':resetpw_hash' => $resetpw_hash,
        ));

        unset( $_SESSION['form_token'] );

		$data = $stmt->fetchAll(PDO::FETCH_ASSOC);

		if (!empty($data))
		{
            $username = $data[0]['login'];
            $id = $data[0]['id'];
            $email = $data[0]['mail'];
			if ($data[0]['confirmed'])
			{
                try {
                    $stmt = $DB->prepare('UPDATE users SET resetpw_hash = null, password = :password WHERE resetpw_hash = :resetpw_hash');

                    $stmt->execute(array(
                        ':resetpw_hash' => $resetpw_hash,
                        ':password' => $hashed_password
                    ));

                    $_SESSION['login'] = $username;
                    $_SESSION['id'] = $id;
                    $_SESSION['email'] = $email;
                    header("Location: ../index.php");
            } catch(Exception $e) {
                    print("Error QUERY ! ". $e->getMessage() ."<br />");
                    die();
                }
			}
			else
            {
                try {
                    $stmt = $DB->prepare('UPDATE users SET resetpw_hash = null, password = :password WHERE resetpw_hash = :resetpw_hash');

                    $stmt->execute(array(
                        ':resetpw_hash' => $resetpw_hash,
                        ':password' => $hashed_password
                    ));

                    header("Location: ../auth.php?action=login&msg=" . urlencode("Please confirm your account"));
                } catch(Exception $e) {
                    print("Error QUERY ! ". $e->getMessage() ."<br />");
                    die();
                }
            }
		}
		else
        	header("Location: ../auth.php?action=reset_password&msg=" . urlencode('Invalid hash'));
    }
    catch(Exception $e)
    {
        $message = 'We are unable to process your request. Please try again later';
		header("Location: ../auth.php?action=login&msg=" . urlencode($message));
    }
}
?>
