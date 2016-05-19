<!doctype html>

<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">

		<title>Camagru</title>
		<link rel="stylesheet" href="css/styles.css">
		<link rel="stylesheet" href="css/normalize.css">
	</head>

	<body>
		<?php include $_SERVER['DOCUMENT_ROOT'] . '/partials/Header.php'; ?>
		<div class="center">
			<?php
				if ($_GET['page'] == 'gallery')
					include $_SERVER['DOCUMENT_ROOT'] . '/partials/Gallery.php';
				else {
					include $_SERVER['DOCUMENT_ROOT'] . '/partials/Main.php';
					include $_SERVER['DOCUMENT_ROOT'] . '/partials/Side.php';
				}
			?>
		</div>
		<?php include $_SERVER['DOCUMENT_ROOT'] . '/partials/Footer.php'; ?>

	</body>
</html>
