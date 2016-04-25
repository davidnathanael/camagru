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
		<div class="center">
			<?php
			if (isset($_GET['msg']))
				echo htmlspecialchars($_GET['msg']);
			if ($_GET['action'] == 'login')
				include $_SERVER['DOCUMENT_ROOT'] . '/partials/Login.php';
			else if ($_GET['action'] == 'signup')
				include $_SERVER['DOCUMENT_ROOT'] . '/partials/CreateAccount.php';
			?>
		</div>
		<?php include $_SERVER['DOCUMENT_ROOT'] . '/partials/Footer.php'; ?>
	</body>
</html>
