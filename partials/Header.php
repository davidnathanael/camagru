<header>
	<ul>
		<?php if (!isset($_SESSION['login']))
		{
		?>
			<li><a class="index" href="../index.php">Camagru</a></li>
			<li><a href="../auth.php?action=login">Log in</a></li>
			<li><a href="../auth.php?action=signup">Create Account</a></li>
		<?php
		}
		else
		{
		?>
			<li><a class="index" href="../index.php">Camagru</a></li>
			<li style="float:right"><a href="../auth/logout.php">Log out</a></li>
		<?php
		}
		?>
	<ul>
</header>
