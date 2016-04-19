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
				if ($_GET['action'] == 'sign_in')
					include $_SERVER['DOCUMENT_ROOT'] . '/partials/CreateAccount.php';
				else
				{
					include $_SERVER['DOCUMENT_ROOT'] . '/partials/Main.php';
					include $_SERVER['DOCUMENT_ROOT'] . '/partials/Side.php';
				}
			?>
		</div>
		<?php include $_SERVER['DOCUMENT_ROOT'] . '/partials/Footer.php'; ?>

		<script src="js/webcam.js"></script>
	</body>
</html>