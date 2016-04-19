<?php

$DB_DSN = 'mysql:host=localhost;dbname=db_camagru;charset=utf8';
$DB_USER = 'root';
$DB_PASSWORD = 'root';

$db = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD);
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

?>
