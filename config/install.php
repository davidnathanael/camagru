<?php
include_once('database.php');

try {
	$bdd = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD);
	$bdd->exec("DROP TABLE IF EXISTS users");
	$bdd->exec("CREATE TABLE users(id INT PRIMARY KEY AUTO_INCREMENT, login VARCHAR(255) UNIQUE NOT NULL, mail VARCHAR(255) UNIQUE NOT NULL, confirmed BOOLEAN NOT NULL DEFAULT 0, confirmation_hash VARCHAR(255) NOT NULL, resetpw_hash VARCHAR(255), password VARCHAR(255) NOT NULL, createdAt DATETIME NOT NULL)");
	$bdd->exec("DROP TABLE IF EXISTS photos");
    $bdd->exec("CREATE TABLE photos(id INT PRIMARY KEY AUTO_INCREMENT, user_id INT NOT NULL, img_path VARCHAR(1024) NOT NULL, createdAt TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP)");
	$bdd->exec("DROP TABLE IF EXISTS comments");
    $bdd->exec("CREATE TABLE comments(id INT PRIMARY KEY AUTO_INCREMENT, user_id INT NOT NULL, photo_id INT NOT NULL, content TEXT, createdAt DATETIME NOT NULL)");
	$bdd->exec("DROP TABLE IF EXISTS likes");
    $bdd->exec("CREATE TABLE likes(id INT PRIMARY KEY AUTO_INCREMENT, user_id INT NOT NULL, photo_id INT NOT NULL, createdAt DATETIME NOT NULL)");
	$bdd = null;
} catch(PDOException $e) {
	print("Erreur ! ". $e->getMessage() ."<br />");
	print($e);
	die();
}

if (!file_exists("../img/photos")) mkdir("../img/photos");

header('Location: ../index.php');

?>
