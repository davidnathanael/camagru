<?php

function	throw_error()
{
	echo "ERROR\n";
	exit;
}

function	send_success()
{
	header("Location: index.html");
	echo "OK\n";
	exit;
}

function	create_file($file)
{
	$dir = '../private';
	if (!file_exists($dir))
		mkdir($dir);
	if (!file_exists($file))
		file_put_contents($file, "");
}

function	insert_account($accounts, $login, $password, $file)
{
	$hashed = hash('whirlpool', $password);
	$new_account = array('login' => $login, 'passwd' => $hashed);
	if ($accounts[0] === FALSE)
		$accounts[0] = $new_account;
	else
		$accounts[] = $new_account;
	file_put_contents($file, serialize((array)$accounts));
}

function	create_account($login, $password)
{
	$file = '../private/passwd';
	create_file($file);

	$accounts = (array)unserialize(file_get_contents($file));
	foreach ($accounts as $account) {
		if ($account['login'] === $login)
			throw_error();
	}
	insert_account($accounts, $login, $password, $file);
	send_success();
}

if ($_POST['submit'] !== 'OK')
	throw_error();
if ($_POST['login'] === "" || $_POST['passwd'] === ""
||	$_POST['login'] === NULL || $_POST['passwd'] === NULL)
	throw_error();
else
	create_account($_POST['login'], $_POST['passwd']);


?>