<?php
session_start();
include $_SERVER['DOCUMENT_ROOT'] . '/config/database.php';
include $_SERVER['DOCUMENT_ROOT'] . '/validations/DB_Utils.php';
include $_SERVER['DOCUMENT_ROOT'] . '/validations/Utils.php';

if(( $_POST['mail'] == "" || $_POST['form_token'] == ""))
	header("Location: ../auth.php?action=forgot&msg=" . urlencode('Please enter your email.'));
elseif( $_POST['form_token'] != $_SESSION['form_token'])
	header("Location: ../auth.php?action=forgot&msg=" . urlencode('Invalid form token'));
else
{
    $mail = filter_var($_POST['mail'], FILTER_SANITIZE_STRING);

    try
    {
        $stmt = $DB->prepare('SELECT * FROM users WHERE mail = :mail');

        $stmt->execute(array(
                ':mail' => $mail));

        unset( $_SESSION['form_token'] );

		$data = $stmt->fetchAll(PDO::FETCH_ASSOC);

		if (!empty($data))
		{
            try {
                $resetpw_hash = generateRandomString(50);
                $stmt = $DB->prepare('UPDATE users SET resetpw_hash = :resetpw_hash WHERE mail = :mail');

                $stmt->execute(array(
                        ':resetpw_hash' => $resetpw_hash,
                        ':mail' => $mail));
            } catch(Exception $e) {
                print("Error QUERY ! ". $e->getMessage() ."<br />");
                die();
            }
            $reset_link = "http://" . $_SERVER[HTTP_HOST] . "/auth.php?action=reset_password&hash=" . $resetpw_hash;
            $mail_msg = "To reset your password follow this link : " . $reset_link;
            sendMail($mail, "Reset your password", $mail_msg);
			header("Location: ../auth.php?action=login&msg=" . urlencode("A mail has been sent to reset your password"));
		}
		else
			header("Location: ../auth.php?action=forgot&msg=" . urlencode("Unvalid email"));
    }
    catch(Exception $e)
    {
        $message = 'We are unable to process your request. Please try again later';
		header("Location: ../auth.php?action=login&msg=" . urlencode($message));
    }
}
?>
