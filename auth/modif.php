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

function	insert_new_password($accounts, $account, $index, $new, $file)
{
	$hashed = hash('whirlpool', $new);
	$account['passwd'] = $hashed;
	$accounts[$index] = $account;

	file_put_contents($file, serialize((array)$accounts));
}

function	check_password($old_stored, $old_entered)
{
	$hashed = hash('whirlpool', $old_entered);

	if ($hashed != $old_stored)
		return FALSE;
	return TRUE;
}

function	get_index($accounts, $login)
{
	foreach ($accounts as $index => $account) {
		if ($account['login'] == $login)
			return $index;
	}
	throw_error();
}

function	modif_account($login, $old, $new)
{

	$file = '../private/passwd';

	if(!file_exists($file))
		throw_error();
	$accounts = (array)unserialize(file_get_contents($file));
	$index = get_index($accounts, $login);
	$account = $accounts[$index];
	if (!check_password($account['passwd'], $old))
		throw_error();
	insert_new_password($accounts, $account, $index, $new, $file);
	send_success();
}

if ($_POST['submit'] !== 'OK')
	throw_error("submit != 'OK'");
if ($_POST['login'] === "" || $_POST['oldpw'] === "" 
|| $_POST['newpw'] === "")
	throw_error();
else
	modif_account($_POST['login'], $_POST['oldpw'], $_POST['newpw']);
?>