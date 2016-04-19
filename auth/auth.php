<?php

function	auth($login, $passwd)
{
	$hashed = hash('whirlpool', $passwd);

	if(!file_exists($file))
		return FALSE;
	$accounts = (array)unserialize(file_get_contents($file));
	foreach ($accounts as $account) {
		if ($account['login'] === $login
		&&	$account['passwd'] === $hashed)
			return TRUE;
	}
	return FALSE;
}

?>