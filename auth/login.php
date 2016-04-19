<?php

include('auth.php');

session_start();


function	display_chat()
{
?>
<iframe src="chat.php" height="550" width="100%"></iframe>
<iframe src="speak.php" height="50" width="100%"></iframe>
<?php
}

if (($_POST['login'] && $_POST['passwd']) || $_SESSION['loggued_on_user'] !== "" )
{
	if (auth($_POST['login'], $_POST['passwd']))
		$_SESSION['loggued_on_user'] = $_POST['login'];
	if ($_SESSION['loggued_on_user'] !== "")
		display_chat();
	else
	{
		$_SESSION['loggued_on_user'] = "";
		header("Location: index.html");
	}
}
?>