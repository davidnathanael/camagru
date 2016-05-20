<?php
include_once('database.php');

try {
	$bdd = new PDO("mysql:host=localhost", $DB_USER, $DB_PASSWORD);
	$bdd->exec("DROP DATABASE IF EXISTS db_camagru");
	$bdd->exec("CREATE DATABASE camagru");
	$DB = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD);
	$DB->exec("DROP TABLE IF EXISTS users");
	$DB->exec("CREATE TABLE users(id INT PRIMARY KEY AUTO_INCREMENT, login VARCHAR(255) UNIQUE NOT NULL, mail VARCHAR(255) UNIQUE NOT NULL, confirmed BOOLEAN NOT NULL DEFAULT 0, confirmation_hash VARCHAR(255), resetpw_hash VARCHAR(255), password VARCHAR(255) NOT NULL, createdAt TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP)");
	$DB->exec("DROP TABLE IF EXISTS photos");
    $DB->exec("CREATE TABLE photos(id INT PRIMARY KEY AUTO_INCREMENT, user_id INT NOT NULL, img_path VARCHAR(1024) NOT NULL, createdAt TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP)");
	$DB->exec("DROP TABLE IF EXISTS comments");
    $DB->exec("CREATE TABLE comments(id INT PRIMARY KEY AUTO_INCREMENT, user_id INT NOT NULL, photo_id INT NOT NULL, content TEXT, createdAt TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP)");
	$DB->exec("DROP TABLE IF EXISTS likes");
    $DB->exec("CREATE TABLE likes(id INT PRIMARY KEY AUTO_INCREMENT, user_id INT NOT NULL, photo_id INT NOT NULL, createdAt TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP)");
	$DB = null;

	unset($_SESSION['id'], $_SESSION['login'], $_SESSION['email']);
} catch(PDOException $e) {
	print("Erreur ! ". $e->getMessage() ."<br />");
	print($e);
	die();
}

if (!file_exists("../img/photos")) mkdir("../img/photos");

header('Location: ../index.php');

?>
