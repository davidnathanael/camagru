<?php

include_once $_SERVER['DOCUMENT_ROOT'] . '/config/database.php';

$DB = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD);
$DB->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// function exec_db($query, $bdd) {
// 	try {
// 		$bdd->exec($query);
// 	} catch(PDOException $e) {
// 		print("Error EXEC ! ". $e->getMessage() ."<br />");
// 		print($e."<br />");
// 		die();
// 	}
// }
//
// function query_db($query, $bdd) {
// 	try {
// 		$res = $bdd->query($query);
// 		return $res->fetchAll();
// 	} catch(PDOException $e) {
// 		print("Error QUERY ! ". $e->getMessage() ."<br />");
// 		die();
// 	}
// }
?>
