<!doctype html>

<html lang="en">
	<head>
		<meta charset="utf-8">

		<title>Camagru</title>
		<link rel="stylesheet" href="css/styles.css">
		<link rel="stylesheet" href="css/normalize.css">
	</head>

	<body>
		<?php include $_SERVER['DOCUMENT_ROOT'] . '/partials/Header.php'; ?>
		<div class="billboard">
			<?php
			if (isset($_GET['msg']))
				echo htmlspecialchars($_GET['msg']);
			if ($_GET['action'] == 'login')
				include $_SERVER['DOCUMENT_ROOT'] . '/partials/Login.php';
			else if ($_GET['action'] == 'signup')
				include $_SERVER['DOCUMENT_ROOT'] . '/partials/CreateAccount.php';
			else if ($_GET['action'] == 'forgot')
				include $_SERVER['DOCUMENT_ROOT'] . '/partials/ForgotPassword.php';
			else if ($_GET['action'] == 'reset_password')
				include $_SERVER['DOCUMENT_ROOT'] . '/partials/ResetPassword.php';
			else if ($_GET['action'] == 'confirm')
				include $_SERVER['DOCUMENT_ROOT'] . '/partials/ConfirmAccount.php';
			?>
		</div>
	</body>
</html>
