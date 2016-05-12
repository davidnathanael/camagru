<?php

session_start();

if (isset($_SESSION['login']))
{
	include $_SERVER['DOCUMENT_ROOT'] . '/config/database.php';
	include $_SERVER['DOCUMENT_ROOT'] . '/layouts/MainLayout.php';
}
else
	include $_SERVER['DOCUMENT_ROOT'] . '/layouts/HomeLayout.php';

?>
